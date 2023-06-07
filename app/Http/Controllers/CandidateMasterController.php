<?php

namespace App\Http\Controllers;

use App\Models\CandidateExperience;
use App\Models\CandidateMaster;
use App\Models\CandidateSkills;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class   CandidateMasterController extends Controller
{

public function index(Request $request)
{
    $perPage = $request->query('pagesize', 10); // Number of items per page (default: 10)
    $searchQuery = $request->query('Search');
    $degreeId = $request->query('degree_id');
    $skillId = $request->query('skill_id');
    $recruitmentStatusId = $request->query('recruitment_status_id');

    $query = CandidateMaster::select(
        'candidate_masters.*',
        'recruitment_status_masters.recruitment_status',
        'source_masters.source',
        'degree_masters.degree',
        'mode_of_work_masters.mode_of_work',
        DB::raw('GROUP_CONCAT(skill_masters.skill) AS skills')
    )
        ->leftJoin('recruitment_status_masters', 'candidate_masters.recruitment_status_id', '=', 'recruitment_status_masters.id')
        ->leftJoin('source_masters', 'candidate_masters.source_id', '=', 'source_masters.id')
        ->leftJoin('degree_masters', 'candidate_masters.degree_id', '=', 'degree_masters.id')
        ->leftJoin('mode_of_work_masters', 'candidate_masters.mode_of_work_id', '=', 'mode_of_work_masters.id')
        ->leftJoin('candidate_skills', 'candidate_masters.id', '=', 'candidate_skills.candidate_master_id')
        ->leftJoin('skill_masters', 'candidate_skills.skill_master_id', '=', 'skill_masters.id')
        ->orderByDesc('candidate_masters.id')
        ->groupBy('candidate_masters.id');

    // Apply search filter
    if ($searchQuery) {
        $query->where(function ($q) use ($searchQuery) {
            $q->where('candidate_masters.name', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('skill_masters.skill', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('candidate_masters.total_experience', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('recruitment_status_masters.recruitment_status', 'LIKE', '%' . $searchQuery . '%');
        });
    }

    if ($degreeId) {
        $query->where('candidate_masters.degree_id', $degreeId);
    }
      if ($skillId) {
        $query->whereHas('skills', function ($q) use ($skillId) {
            $q->where('skill_masters.id', $skillId);
        });
    }

    if ($recruitmentStatusId) {
        $query->where('candidate_masters.recruitment_status_id', $recruitmentStatusId);
    }

    $candidateList = $query->paginate($perPage);

    return response()->json([
        'success' => count($candidateList) ? true : false,
        'data' => $candidateList,
    ]);
}


    public function getActiveCandidates()
    {
        
        $candidateList = CandidateMaster::select('candidate_masters.*','recruitment_status_masters.recruitment_status', 'source_masters.source')
                                ->leftJoin('recruitment_status_masters', 'candidate_masters.recruitment_status_id', '=', 'recruitment_status_masters.id')
                                ->leftJoin('source_masters', 'candidate_masters.source_id', '=', 'source_masters.id')
                                ->whereIn('recruitment_status_masters.recruitment_status', ['Applied', 'In Process', 'On Hold'])
                                ->orderByDesc('candidate_masters.id')
                                ->get();
        return response()->json([
            'success' => count($candidateList) ? true : false,
            'data' => $candidateList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|email",
            "contect_no" => "required|min:10|max:12",
            "dob" => "nullable",
            "mode_of_work_id" => "required",
            "degree_id" => "required",
            "passing_year" => "nullable",
            "passing_grade" => "nullable",
            "total_experience" => "required",
            "current_salary" => "required",
            "expected_salary" => "required",
            "is_negotiable" => "nullable",
            "notice_period" => "nullable",
            "address" => "nullable",
            "city" => "nullable",
            "state" => "nullable",
            "countary" => "nullable",
            "resume_id" => "nullable",
            "remarks" => "nullable",
            "recruitment_status_id" => "nullable",
            "source_id" => "nullable",
            "skills" => "nullable",
            "previs_companies" => "nullable",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        } else {

            $validated = $validator->validated();
            $convertedName =  str_replace(' ', '-', $request->name);
            $fileName = '';
            if (isset($request->resume_id) && !is_null($request->resume_id)) {
                $fileName = $convertedName . '-' . time() . '.' . $request->resume_id->getClientOriginalExtension();
            }

            $path = Storage::disk('s3')->put("files/" . $fileName, file_get_contents($request->resume_id), 'public');
            $url = Storage::disk('s3')->url($path);


            $validated['resume_id'] = 'https://weybee-recruitment.s3.ap-southeast-1.amazonaws.com/files/' . $fileName;
            $candidate = CandidateMaster::create($validated);

            // $candidate->Skills()->attach($validated['skills']);

            if (isset($validated['skills'])) {
                foreach ($validated['skills'] as $skill) {
                    CandidateSkills::create([
                        "candidate_master_id" => $candidate->id,
                        "skill_master_id" => $skill['skill_master_id'],
                        "experience" => $skill['experience'],
                        "self_rating" => $skill['self_rating']
                    ]);
                }
            }

            if (isset($validated['previs_companies'])) {
                foreach ($validated['previs_companies'] as $companiee) {
                    CandidateExperience::create([
                        "candidate_master_id" => $candidate->id,
                        "coumpany_name" => $companiee["coumpany_name"],
                        "from" => $companiee["from"],
                        "to" =>  $companiee["to"],
                    ]);
                }
            }
            $candidatemaster["source"] = $candidate->Source;
            $candidatemaster["recruitment_status"] = $candidate->Recruitment_Status;
            $candidatemaster["degree"] = $candidate->Degree;
            $candidatemaster["previs_companies"] = $candidate->CandidateExperience;
            $candidatemaster["mode_of_work"] = $candidate->ModeOfWork;
            $candidatemaster["skills"] = DB::select('SELECT * from candidate_skills 
            LEFT JOIN skill_masters on skill_masters.id= skill_master_id
             WHERE candidate_master_id = ?', [$candidate->id]);

            return response()->json([
                'success' => true,
                'candidate' => $candidatemaster,
            ]);
        }
        return response()->json([
            'success' => false
        ]);
    }

    public function show(CandidateMaster $candidatemaster)
    {
        // $candidatemaster["skills"] = $candidatemaster->Skills;
        $candidatemaster["source"] = $candidatemaster->Source;
        $candidatemaster["recruitment_status"] = $candidatemaster->Recruitment_Status;
        $candidatemaster["degree"] = $candidatemaster->Degree;
        $candidatemaster["previs_companies"] = $candidatemaster->CandidateExperience;
        $candidatemaster["mode_of_work"] = $candidatemaster->ModeOfWork;
        $candidatemaster["skills"] = DB::select('SELECT * from candidate_skills 
        LEFT JOIN skill_masters on skill_masters.id= skill_master_id
         WHERE candidate_master_id = ?', [$candidatemaster->id]);

        return response()->json([
            'success' => $candidatemaster ? true : false,
            'candidate' => $candidatemaster,
        ]);
    }

    public function update(Request $request, CandidateMaster $candidatemaster)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|email",
            "contect_no" => "required|min:10|max:12",
            "dob" => "nullable",
            "mode_of_work_id" => "required",
            "degree_id" => "required",
            "passing_year" => "nullable",
            "passing_grade" => "nullable",
            "total_experience" => "required",
            "current_salary" => "required",
            "expected_salary" => "required",
            "is_negotiable" => "nullable",
            "notice_period" => "nullable",
            "address" => "nullable",
            "city" => "nullable",
            "state" => "nullable",
            "countary" => "nullable",
            "resume_id" => "nullable",
            "remarks" => "nullable",
            "recruitment_status_id" => "nullable",
            "source_id" => "nullable",
            "skills" => "nullable",
            "previs_companies" => "nullable",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        } else {

            $validated = $validator->validated();

            if ($request->hasFile('resume_id')) {
                // $fileName = time() . '.' . $request->resume_id->getClientOriginalExtension();
                $convertedName =  str_replace(' ', '-', $request->name);
                $fileName = '';
                if (isset($request->resume_id) && !is_null($request->resume_id)) {
                    $fileName = $convertedName . '-' . time() . '.' . $request->resume_id->getClientOriginalExtension();
                }
                $path = Storage::disk('s3')->put("files/" . $fileName, file_get_contents($request->resume_id), 'public');
                $url = Storage::disk('s3')->url($path);
                $validated['resume_id'] = 'https://weybee-recruitment.s3.ap-southeast-1.amazonaws.com/files/' . $fileName;
            }

            if(isset($validated['dob'])) {
                $validated['dob'] =  $validated['dob'];
            }
            $candidatemaster->update($validated);

            CandidateExperience::where('candidate_master_id', $candidatemaster->id)->delete();
            CandidateSkills::where('candidate_master_id', $candidatemaster->id)->delete();

            if (isset($validated['skills'])) {
                foreach ($validated['skills'] as $skill) {
                    CandidateSkills::create([
                        "candidate_master_id" => $candidatemaster->id,
                        "skill_master_id" => $skill['skill_master_id'],
                        "experience" => $skill['experience'],
                        "self_rating" => $skill['self_rating']
                    ]);
                }
            }

            if (isset($validated['previs_companies'])) {
                foreach ($validated['previs_companies'] as $companiee) {
                    CandidateExperience::create([
                        "candidate_master_id" => $candidatemaster->id,
                        "coumpany_name" => $companiee["coumpany_name"],
                        "from" => $companiee["from"],
                        "to" =>  $companiee["to"],
                    ]);
                }
            }

            $candidatemaster["source"] = $candidatemaster->Source;
            $candidatemaster["recruitment_status"] = $candidatemaster->Recruitment_Status;
            $candidatemaster["degree"] = $candidatemaster->Degree;
            $candidatemaster["previs_companies"] = $candidatemaster->CandidateExperience;
            $candidatemaster["mode_of_work"] = $candidatemaster->ModeOfWork;
            $candidatemaster["skills"] = DB::select('SELECT * from candidate_skills 
            LEFT JOIN skill_masters on skill_masters.id= skill_master_id
             WHERE candidate_master_id = ?', [$candidatemaster->id]);

            return response()->json([
                'success' => true,
                'candidate' => $candidatemaster,
            ]);
        }
        return response()->json([
            "success" => false,
        ], 400);
    }

    public function destroy(CandidateMaster $candidatemaster)
    {
        $candidatemaster->delete();
        $candidateList = CandidateMaster::get();
        return response()->json([
            'success' => count($candidateList) ? true : false,
            'data' => $candidateList,
        ]);
    }
}

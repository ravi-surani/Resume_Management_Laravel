<?php

namespace App\Http\Controllers;

use App\Mail\interviewMail;
use App\SendSMS;
use App\Models\CandidateMaster;
use App\Models\InterviewerMaster;
use App\Models\Interviews;
use App\Models\InteviewScheduleMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Mail;

class InterviewsController extends Controller
{
    public function index()
    {



        // $details = array(
        //     'name' => "Alex"
        // );
        // Mail::send(['text' => 'interviewMail'], $details, function ($message) {
        //     $message->to('revi@weybee.com', 'W3SCHOOLS')
        //         ->subject('Basic test eMail from W3schools.');
        //     $message->from('revi@weybee.com', 'Alex');
        // });



        // $InterviewList = DB::select('SELECT
        // interviews.id,interviews.date,candidate_masters.name,candidate_masters.email,candidate_masters.contect_no,interview_type_masters.interview_type,recruitment_status_masters.recruitment_status
        // from interviews LEFT JOIN candidate_masters on candidate_masters.id = interviews.candidate_master_id 
        // LEFT JOIN interview_type_masters on interview_type_masters.id = interview_type_id
        // LEFT JOIN recruitment_status_masters on recruitment_status_masters.id = candidate_masters.recruitment_status_id;');

        $InterviewList = Interviews::with('Candidate', 'Interview_type')->get();
        return response()->json([
            'success' => count($InterviewList) ? true : false,
            'data' => $InterviewList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "candidate_master_id" => 'required',
            "interview_type_id" => 'required',
            "interviewer_id" => 'required',
            "interview_mode_id" => 'required',
            "date" => 'required',
            "remarks" => 'nullable',
            "location_link" => 'nullable',
            "total_rating" => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        } else {
            $validated = $validator->validated();
            $interview = Interviews::create($validated);
            $interviewer = InterviewerMaster::where('id', $validated['interviewer_id'])->first();
            $candidateDetails = CandidateMaster::with('candidateSkills.skillMaster')->where('id', $validated['candidate_master_id'])->first()->toArray();
            
            $interviewDetail = Interviews::where('id', $interview->id)->with(
                "Interview_type",
                "Interviewer_id",
                "Interview_mode",
            )->first()->toArray();

                // return $interviewDetail;

            // $interviewerDetails = InterviewerMaster::where('id', $interview->interviewer_id)->first();
            // return $candidateDetails;
            $skillData = $candidateDetails['candidate_skills'];
            $formattedSkills = array_map(function ($skillData) {
                if ($skillData['experience'] == 1) {
                    // return $skillData['skill_master']['skill'] . ' => ' . $skillData['experience'].' year of experience';
                    return $skillData['skill_master']['skill'] . ' (' . $skillData['experience'].' year' . ')';
                }
                // return $skillData['skill_master']['skill'] . ' => ' . $skillData['experience'].' years of experience';
                return $skillData['skill_master']['skill'] . ' (' . $skillData['experience'].' years' . ')';
            }, $skillData);
            
            // Use implode to join the strings together with a comma
            $skillDetails = implode(', ', $formattedSkills);
            
            $dateTime = Carbon::parse($validated['date']);
            $formattedDateTime = $dateTime->format('d-M-Y H:i');

            $details = [];
            $details['name'] = $interviewDetail['interviewer_id']['name'];
            $details['candidateName'] = $candidateDetails['name'];
            $details['skills'] = $skillDetails;
            $details['date'] = $formattedDateTime;
            $details['type'] = $interviewDetail['interview_type']['interview_type'];
            $details['mode'] = $interviewDetail['interview_mode']['interview_mode'];
            $details['details'] = $interviewDetail['location_link'];


            // $enc = encrypt(['cid' => $validated['candidate_master_id'], 'iid' => $interview['id']]);
            // Encrypt the array
            $encrypted = encrypt(['cid' => $validated['candidate_master_id'], 'iid' => $interview['id']]);

            $enc = time();
            $InteviewScheduleMapping = new InteviewScheduleMapping();
            $InteviewScheduleMapping->unique_code = $enc;
            $InteviewScheduleMapping->encrypted_data = $encrypted;
            $InteviewScheduleMapping->save();

            // Decrypt the encrypted message
            // $decrypted = decrypt($encrypted);

            $interviewLink = url('/')."/reviewsubmit/".$interview['id']."/" . $enc;
            $details['linkToSubmitMarks'] = $interviewLink;
            
            Mail::to($interviewer->email)->send(new interviewMail($details));
            try {
                $template_name = 'recruitment'; 
                $message = 'Interview Details';
    
                $params = [
                    ['type' => 'text', 'text' => $details['name']],
                    ['type' => 'text', 'text' => $details['candidateName']],
                    ['type' => 'text', 'text' => $details['skills']],
                    ['type' => 'text', 'text' => $details['date']],
                    ['type' => 'text', 'text' => $details['type']],
                    ['type' => 'text', 'text' => $details['mode']],
                    ['type' => 'text', 'text' => $details['details']],
                    ['type' => 'text', 'text' =>  $interviewLink],
                ];
                SendSMS::instance()->sendmsg($interviewDetail['interviewer_id']['contect_no'], $message, $template_name, $params);
            } catch (\Throwable $th) {
            //     // throw $th;
            }
            return response()->json([
                'success' => true,
                'interview_details' => $interview,
            ]);
        }
        $details = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp'
        ];



        return response()->json([
            "success" => false,
        ]);
    }

    public function show(Request $request)
    {
        $interviews = Interviews::where('candidate_master_id', $request->id)->with(
            "Interview_type",
            "Interviewer_id",
            "Interview_mode",
        )->get();
        $candidate = CandidateMaster::where('id', $request->id)->with('Recruitment_Status', 'Source', 'ModeOfWork')->get();

        $skills = DB::select('SELECT * from candidate_skills 
         LEFT JOIN skill_masters on skill_masters.id= skill_master_id
          WHERE candidate_master_id = ?', [$request->id]);

        // dd($request->id);

        return response()->json([
            'success' => $candidate ? true : false,
            'data' => ["interviews" => $interviews, "candidate" => $candidate, "skills" => $skills]
        ]);
    }
    public function interviewById(Request $request)
    {
        $interview = Interviews::where('id', $request->id)->with(
            "Interview_type",
            "Interviewer_id",
            "Interview_mode",
        )->first();

        $candidate = CandidateMaster::where('id', $interview->candidate_master_id)->with('Recruitment_Status', 'Source', 'ModeOfWork')->get();

        return response()->json([
            'success' => $candidate ? true : false,
            'data' => ["interview" => $interview, "candidate" => $candidate]
        ]);
    }

    public function edit(Interviews $interviews)
    {
        //
    }

    public function update(Request $request, Interviews $interviews)
    {
        $validator = Validator::make($request->all(), [
            "candidate_master_id" => 'required',
            "interview_type_id" => 'required',
            "interviewer_id" => 'required',
            "interview_mode_id" => 'required',
            "date" => 'required',
            "remarks" => 'nullable',
            'status' => 'nullable',
            'location_link' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        } else {
            $validated = $validator->validated();
            $interviews->update($validated);

            return response()->json([
                'success' => true,
                'interview_details' => $interviews,
            ]);
        }

        return response()->json([
            "success" => false,
        ]);
    }

    public function destroy(Interviews $interviews)
    {
        $interviews->delete();
        $InterviewList = Interviews::get();
        return response()->json([
            'success' => count($InterviewList) ? true : false,
            'data' => $InterviewList,
        ]);
    }
}

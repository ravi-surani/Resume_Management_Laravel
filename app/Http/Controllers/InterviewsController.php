<?php

namespace App\Http\Controllers;

use App\Mail\interviewMail;
use App\Models\CandidateMaster;
use App\Models\InterviewerMaster;
use App\Models\Interviews;
use App\Models\InteviewScheduleMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Facades\Validator;

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
            Mail::to($interviewer->email)->send(new interviewMail(["url" => $interviewLink]));
            // ---------whatsapp
            try {
                $template_name = 'client_leave_reminder'; 
                $candidateDetails = CandidateMaster::select('contect_no','name')->where('id', $validated['candidate_master_id'])->first();
                $message = 'Interview Details';
    
                $params = [
                    ['type' => 'text', 'text' =>  $interviewLink],
                    ['type' => 'text', 'text' => $candidateDetails['name']],
                ];
                SendSMS::instance()->sendmsg($candidateDetails['contect_no'], $message, $template_name, $params);
            } catch (\Throwable $th) {
                //throw $th;
            }
            // ---------whatsapp

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

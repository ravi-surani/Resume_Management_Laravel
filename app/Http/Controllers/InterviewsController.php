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


        $InterviewList = Interviews::with('Candidate', 'Interview_type')->get();
        return response()->json([
            'success' => count($InterviewList) ? true : false,
            'data' => $InterviewList,
        ]);
    }
    public function getActiveCandidateInterview(Request $request)
    {
        $perPage = $request->query('pagesize', 10); // Number of items per page (default: 10)
        $searchQuery = $request->query('Search');
        $interviewerId = $request->query('interviewer_id');
        $interviewTypeId = $request->query('interview_type_id');
        $interviewModeId = $request->query('interview_mode_id');

    
        $query = Interviews::with('Candidate', 'Interview_type', 'Interviewer_id', 'Interview_mode')
            ->select('candidate_masters.*', 'interviews.*', 'recruitment_status_masters.recruitment_status')
            ->leftJoin('candidate_masters', 'interviews.candidate_master_id', '=', 'candidate_masters.id')
            ->leftJoin('recruitment_status_masters', 'candidate_masters.recruitment_status_id', '=', 'recruitment_status_masters.id')
            ->whereIn('recruitment_status_masters.recruitment_status', ['Applied', 'In Process', 'On Hold']);
    
        if ($searchQuery) {
            $query->whereHas('Candidate', function ($q) use ($searchQuery) {
                $q->where('name', 'LIKE', '%' . $searchQuery . '%');
            });
        }
        if ($interviewerId) {
            $query->where('interviews.interviewer_id', $interviewerId);
        }
        if ($interviewTypeId) {
            $query->where('interviews.interview_type_id', $interviewTypeId);
        }
        if ($interviewModeId) {
            $query->where('interviews.interview_mode_id', $interviewModeId);
        }
    
    
        $InterviewList = $query->paginate($perPage);
    
        return response()->json([
            'success' => $InterviewList->count() > 0,
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
            
            $skillDetails = $this->getSkillDetails($candidateDetails);
            
            $this->sendNotification($validated['candidate_master_id'], $validated['date'], $skillDetails, $interview, $candidateDetails, $interviewer);
            
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

    private function getSkillDetails($candidateDetails) {

            $skillData = $candidateDetails['candidate_skills'];
            $formattedSkills = array_map(function ($skillData) {
                if ($skillData['experience'] == 1) {
                    return $skillData['skill_master']['skill'] . ' (' . $skillData['experience'].' year' . ')';
                }
                return $skillData['skill_master']['skill'] . ' (' . $skillData['experience'].' years' . ')';
            }, $skillData);
            
            // Use implode to join the strings together with a comma
            $skillDetails = implode(', ', $formattedSkills);
            return $skillDetails;
    }

    private function sendNotification($candidate_master_id, $date, $skillDetails, $interview, $candidateDetails, $interviewer) {
        $dateTime = Carbon::parse($date);
            $formattedDateTime = $dateTime->format('d-M-Y H:i');
            
            $interviewDetail = Interviews::where('id', $interview->id)->with(
                "Interview_type",
                "Interviewer_id",
                "Interview_mode",
            )->first()->toArray();

            $details = [];
            $details['name'] = $interviewDetail['interviewer_id']['name'];
            $details['candidateName'] = $candidateDetails['name'];
            $details['skills'] = $skillDetails;
            $details['date'] = $formattedDateTime;
            $details['type'] = $interviewDetail['interview_type']['interview_type'];
            $details['mode'] = $interviewDetail['interview_mode']['interview_mode'];
            $details['details'] = $interviewDetail['location_link'];

            // Encrypt the array
            $encrypted = encrypt(['cid' => $candidate_master_id, 'iid' => $interview['id']]);

            $enc = time();
            $InteviewScheduleMapping = new InteviewScheduleMapping();
            $InteviewScheduleMapping->unique_code = $enc;
            $InteviewScheduleMapping->encrypted_data = $encrypted;
            $InteviewScheduleMapping->save();

            $eventTitle = 'Interview with ' . $candidateDetails['name'];
            $fromDateTime = Carbon::createFromFormat('d-M-Y H:i', $formattedDateTime);
            $toDateTime = $fromDateTime->copy()->addHour();// Add one hour to the "from" time to get the "to" time
            $formattedFromDateTime = $fromDateTime->format('Ymd\THis');// Format the dates according to the specified format
            $formattedToDateTime = $toDateTime->format('Ymd\THis');
            $combinedDates = $formattedFromDateTime . '/' . $formattedToDateTime;// Combine the formatted dates

            $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$eventTitle}&dates={$combinedDates}";            

            $interviewLink = url('/')."/reviewsubmit/".$interview['id']."/" . $enc;
            $details['linkToSubmitMarks'] = $interviewLink;
            $details['googleCalendarUrl'] = $googleCalendarUrl;
            
            Mail::to($interviewer->email)->send(new interviewMail($details,));
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
    }

    public function show(Request $request)
    {
        $interviews = Interviews::where('candidate_master_id', $request->id)->with(
            "Interview_type",
            "Interviewer_id",
            "Interview_mode",
        )->get();
        $candidate = CandidateMaster::where('id', $request->id)->with('Recruitment_Status', 'Source', 'ModeOfWork', 'Degree')->get();

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

        $candidate = CandidateMaster::where('id', $interview->candidate_master_id)
                                    ->with('Recruitment_Status', 'Source', 'ModeOfWork', 'Degree', 'Skills')
                                    ->get();
        $skills = DB::select('SELECT * from candidate_skills 
                        LEFT JOIN skill_masters on skill_masters.id= skill_master_id
                        WHERE candidate_master_id = ?', [$request->id]);


        return response()->json([
            'success' => $candidate ? true : false,
            'data' => ["interview" => $interview, "candidate" => $candidate,"skills" => $skills]
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
            $interviewer = InterviewerMaster::where('id', $validated['interviewer_id'])->first();
            $candidateDetails = CandidateMaster::with('candidateSkills.skillMaster')->where('id', $validated['candidate_master_id'])->first()->toArray();
            
            $skillDetails = $this->getSkillDetails($candidateDetails);
            
            $this->sendNotification($validated['candidate_master_id'], $validated['date'], $skillDetails, $interviews, $candidateDetails, $interviewer);


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

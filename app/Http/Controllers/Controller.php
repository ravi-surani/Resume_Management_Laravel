<?php

namespace App\Http\Controllers;

use App\Models\CandidateSkills;
use App\Models\Interviews;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function reviewSubmitForm(Request $request)
    {

        $data = decrypt($request->route('details'));

        $interviews = Interviews::where('id', $data['iid'])->with(
            "Interview_type",
            "Interviewer_id",
            "Interview_mode",
            "Candidate",

        )->first();

        if ($interviews->status) {
            $skills = DB::select('SELECT * from candidate_skills 
         LEFT JOIN skill_masters on skill_masters.id= skill_master_id
          WHERE candidate_master_id = ?', [$data['cid']]);


            return view('interviewsFrom', compact(
                "interviews",
                "skills",
            ));
        } else {
            return view('thankYou', compact(
                "interviews",
            ));
        }
    }

    public function reviewSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "candidateId" => "required",
            "interviewId" => "required",
            "total_rating" => "required",
            "self_rating" => "nullable",
            "theory_rating" => "nullable",
            "practical_rating" => "nullable",
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)
                ->withInput();
        } else {
            $data = $validator->validated();


            Interviews::where('id', $data['interviewId'])->update(['total_rating' => $data['total_rating'], "status" => false]);

            if (isset($data['self_rating'])) {
                foreach ($data['self_rating'] as $key) {
                    CandidateSkills::where([['candidate_master_id', $data['candidateId']], ['skill_master_id', $key]])->update(['self_rating' => $data['skill'][$key]]);
                }
            }
            if (isset($data['theory_rating'])) {
                foreach ($data['theory_rating'] as $key) {

                    CandidateSkills::where([['candidate_master_id', $data['candidateId']], ['skill_master_id', $key]])->update(['theory_rating' => $data['theory_rating'][$key]]);
                }
            }
            if (isset($data['practical_rating'])) {

                foreach ($data['practical_rating'] as $key) {
                    CandidateSkills::where([['candidate_master_id', $data['candidateId']], ['skill_master_id', $key]])->update(['practical_rating' => $data['practical_rating'][$key]]);
                }
            }
            return view('thankYou');
        }
    }

    public function allUsers(Request $request)
    {
        $user = User::all();
        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function remove(Request $request)
    {
        $userId = $request->route('id');
        User::where('id', $userId)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Rremoved',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\InterviewerMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterviewerMasterController extends Controller
{
    public function index()
    {
        $interviewerList = InterviewerMaster::get();
        return response()->json([
            'success' => count($interviewerList) ? true : false,
            'data' => $interviewerList,
        ]);
    }

    public function getAactiveInterviewer()
    {
        $interviewerList = InterviewerMaster::where('status', 1)->get();
        return response()->json([
            'success' => count($interviewerList) ? true : false,
            'data' => $interviewerList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            "email" => "required|email|unique:interviewer_masters",
            "contect_no" => "required|min:10|max:12|unique:interviewer_masters",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $Interviewer = InterviewerMaster::create($validated);

        return response()->json([
            'success' => true,
            'Interviewer' => $Interviewer,
        ]);
    }

    public function update(Request $request, InterviewerMaster $interviewermaster)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            "email" => "required|email",
            "contect_no" => "required|min:10|max:12",
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $interviewermaster->update($validated);

        return response()->json([
            'success' => true,
            'Interviewer' => $interviewermaster,
        ]);
    }

    public function destroy(InterviewerMaster $interviewermaster)
    {
        $interviewermaster->delete();
        $interviewerList = InterviewerMaster::get();
        return response()->json([
            'success' => true,
            'data' => $interviewerList,
        ]);
    }
}

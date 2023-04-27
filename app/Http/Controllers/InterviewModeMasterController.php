<?php

namespace App\Http\Controllers;

use App\Models\InterviewModeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterviewModeMasterController extends Controller
{
    public function index()
    {
        $InterviewModeList = InterviewModeMaster::get();
        return response()->json([
            'success' => count($InterviewModeList) ? true : false,
            'data' => $InterviewModeList,
        ]);
    }

    public function getAactiveInterviewMode()
    {
        $InterviewModeList = InterviewModeMaster::where('status', 1)->get();
        return response()->json([
            'success' => count($InterviewModeList) ? true : false,
            'data' => $InterviewModeList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'interview_mode' => 'required|string',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $interviewMode = InterviewModeMaster::create($validated);

        return response()->json([
            'success' => true,
            'interview_mode' => $interviewMode,
        ]);
    }

    public function update(Request $request, InterviewModeMaster $interviewmodemaster)
    {
        $validator = Validator::make($request->all(), [
            'interview_mode' => 'required|string',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $interviewmodemaster->update($validated);

        return response()->json([
            'success' => true,
            'interview_mode' => $interviewmodemaster,
        ]);
    }


    public function destroy(InterviewModeMaster $interviewmodemaster)
    {
        $interviewmodemaster->delete();

        $InterviewModeList = InterviewModeMaster::get();

        return response()->json([
            'success' => true,
            'data' => $InterviewModeList,
        ]);
    }
}

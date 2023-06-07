<?php

namespace App\Http\Controllers;

use App\Models\InterviewTypeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterviewTypeMasterController extends Controller
{
    public function index()
    {
        $interviewTypeList = InterviewTypeMaster::get();
        return response()->json([
            'success' => count($interviewTypeList) ? true : false,
            'data' => $interviewTypeList,
        ]);
    }

    public function getAactiveInterviewType()
    {
        $interviewTypeList = InterviewTypeMaster::where('status', 1)->get();
        return response()->json([
            'success' => count($interviewTypeList) ? true : false,
            'data' => $interviewTypeList,
        ]);
    }

    public function getInterviewTypeById(Request $request, $id)
    {
        $interviewType = InterviewTypeMaster::find($id);
    
        if ($interviewType) {
            return response()->json([
                'success' => true,
                'data' => $interviewType,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Interview Type not found',
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'interview_type' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $validated['status'] = $validated['status'] == 'true' ? 1 : 0;
        $interviewType = InterviewTypeMaster::create($validated);

        return response()->json([
            'success' => true,
            'interview_type' => $interviewType,
        ]);
    }

    public function update(Request $request, InterviewTypeMaster $interviewtypemaster)
    {
        $validator = Validator::make($request->all(), [
            'interview_type' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $interviewtypemaster->update($validated);

        return response()->json([
            'success' => true,
            'interview_type' => $interviewtypemaster,
        ]);
    }

    public function destroy(InterviewTypeMaster $interviewtypemaster)
    {
        $interviewtypemaster->delete();

        $interviewTypeList = interviewTypeMaster::get();

        return response()->json([
            'success' => true,
            'data' => $interviewTypeList,
        ]);
    }
}

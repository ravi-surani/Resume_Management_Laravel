<?php

namespace App\Http\Controllers;

use App\Models\InterviewMaster;
use App\Models\Interviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterviewMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $InterviewList = Interviews::with(
            "Candidate",
            "Interview_type",
            "Interviewer_id",
            "Interview_mode",
        )->get();
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
            "total_rating" => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        } else {
            $validated = $validator->validated();
            // dd($validated);
            $interview = Interviews::create($validated);

            return response()->json([
                'success' => true,
                'interview_details' => $interview,
            ]);
        }

        return response()->json([
            "success" => false,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Interviews $interviews)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interviews $interviews)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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

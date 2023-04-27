<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentStatusMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecruitmentStatusMasterController extends Controller
{
    public function index()
    {
        $recruitmentStatusList = RecruitmentStatusMaster::get();
        return response()->json([
            'success' => count($recruitmentStatusList) ? true : false,
            'data' => $recruitmentStatusList,
        ]);
    }

    public function getActiveRecruitmentStatus()
    {
        $recruitmentStatusList = RecruitmentStatusMaster::where('status', 1)->get();
        return response()->json([
            'success' => count($recruitmentStatusList) ? true : false,
            'data' => $recruitmentStatusList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recruitment_status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $recruitmentStatus = RecruitmentStatusMaster::create($validated);

        return response()->json([
            'success' => true,
            'recruitment_status' => $recruitmentStatus,
        ]);
    }

    public function update(Request $request, RecruitmentStatusMaster $recruitmentstatusmaster)
    {
        $validator = Validator::make($request->all(), [
            'recruitment_status' => 'required|string',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();

        $recruitmentstatusmaster->update($validated);

        return response()->json([
            'success' => true,
            'recruitment_status' => $recruitmentstatusmaster,
        ]);
    }

    public function destroy(RecruitmentStatusMaster $recruitmentstatusmaster)
    {
        $recruitmentstatusmaster->delete();

        $recruitmentStatusList = RecruitmentStatusMaster::get();

        return response()->json([
            'success' => true,
            'data' => $recruitmentStatusList,
        ]);
    }
    
}
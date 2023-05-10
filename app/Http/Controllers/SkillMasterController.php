<?php

namespace App\Http\Controllers;

use App\Models\SkillMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillMasterController extends Controller
{
    public function index()
    {
        $skillList = SkillMaster::with('skillType')->get();
        return response()->json([
            'success' => count($skillList) ? true : false,
            'data' => $skillList,
        ]);
    }

    public function getActiveSkill()
    {
        $skillList = SkillMaster::with('skillType')->where('status', 1)->get();
        return response()->json([
            'success' => count($skillList) ? true : false,
            'data' => $skillList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skill' => 'required|string',
            'skill_type_id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $validated['status'] = $validated['status'] == 'true' ? 1 : 0;
        $skilltype = SkillMaster::create($validated);

        return response()->json([
            'success' => true,
            'skill' => $skilltype,
        ]);
    }

    public function update(Request $request, SkillMaster $skillmaster)
    {
        $validator = Validator::make($request->all(), [
            'skill' => 'required|string',
            'skill_type_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();

        $skillmaster->update($validated);

        return response()->json([
            'success' => true,
            'skill' => $skillmaster,
        ]);
    }

    public function destroy(SkillMaster $skillmaster)
    {
        $skillmaster->delete();
        $skillList = SkillMaster::with('skillType')->get();
        return response()->json([
            'success' => true,
            'data' => $skillList,
        ]);
    }
}

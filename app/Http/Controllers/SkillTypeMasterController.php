<?php

namespace App\Http\Controllers;

use App\Models\SkillTypeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillTypeMasterController extends Controller
{
    public function index()
    {
        $skilltypeList = SkillTypeMaster::get();
        return response()->json([
            'success' => count($skilltypeList) ? true : false,
            'data' => $skilltypeList,
        ]);
    }

    public function getActiveSkillType()
    {
        $skilltypeList = SkillTypeMaster::where('status', 1)->get();
        return response()->json([
            'success' => count($skilltypeList) ? true : false,
            'data' => $skilltypeList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skill_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $skilltype = SkillTypeMaster::create($validated);

        return response()->json([
            'success' => true,
            'skill_type' => $skilltype,
        ]);
    }

    public function update(Request $request, SkillTypeMaster $skilltypemaster)
    {
        $validator = Validator::make($request->all(), [
            'skill_type' => 'required|string',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();

        $skilltypemaster->update($validated);

        return response()->json([
            'success' => true,
            'skill_type' => $skilltypemaster,
        ]);
    }

    public function destroy(SkillTypeMaster $skilltypemaster)
    {
        $skilltypemaster->delete();
        $skilltypeList = SkillTypeMaster::get();
        return response()->json([
            'success' => true,
            'data' => $skilltypeList,
        ]);
    }
}

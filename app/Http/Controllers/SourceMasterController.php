<?php

namespace App\Http\Controllers;

use App\Models\SourceMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SourceMasterController extends Controller
{
    public function index()
    {
        $sourceList =  SourceMaster::all();
        return response()->json([
            'success' => count($sourceList) ? true : false,
            'data' => $sourceList,
        ]);
    }

    public function getActiveSource()
    {
        $sourceList =  SourceMaster::where('status', 1)->get();
        return response()->json([
            'success' => count($sourceList) ? true : false,
            'data' => $sourceList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $source = SourceMaster::create($validated);

        return response()->json([
            'success' => true,
            'source' => $source,
        ]);
    }

    public function update(Request $request, SourceMaster $sourcemaster)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required|string',
            'status' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();

        $sourcemaster->update($validated);

        return response()->json([
            'success' => true,
            'source' => $sourcemaster,
        ]);
    }

    public function destroy(SourceMaster $sourcemaster)
    {
        $sourcemaster->delete();
        $sourceList =  SourceMaster::all();

        return response()->json([
            'success' => true,
            'data' => $sourceList,
        ]);
    }
}

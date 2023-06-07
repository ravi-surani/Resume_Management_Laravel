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

    public function getSourceById(Request $request, $id)
    {
        $source = SourceMaster::find($id);
    
        if ($source) {
            return response()->json([
                'success' => true,
                'data' => $source,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Source not found',
            ], 404);
        }
    }
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required|string',
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
            'status' => 'required',
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

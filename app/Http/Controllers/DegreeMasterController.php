<?php

namespace App\Http\Controllers;

use App\Models\DegreeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DegreeMasterController extends Controller
{
    public function index()
    {
        $degreeList = DegreeMaster::get();
        return response()->json([
            'success' => count($degreeList) ? true : false,
            'data' => $degreeList,
        ]);
    }

    public function getAactiveDegree()
    {
        $degreeList = DegreeMaster::where('status', 1)->get();
        return response()->json([
            'success' => count($degreeList) ? true : false,
            'data' => $degreeList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'degree' => 'required|string',
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
        $degree = DegreeMaster::create($validated);

        return response()->json([
            'success' => true,
            'degree' => $degree,
        ]);
    }

    public function update(Request $request, DegreeMaster $degreemaster)
    {
        $validator = Validator::make($request->all(), [
            'degree' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $degreemaster->update($validated);

        return response()->json([
            'success' => true,
            'degree' => $degreemaster,
        ]);
    }

    public function destroy(DegreeMaster $degreemaster)
    {
        $degreemaster->delete();
        $degreeList = DegreeMaster::get();
        return response()->json([
            'success' => count($degreeList) ? true : false,
            'data' => $degreeList,
        ]);
    }
}

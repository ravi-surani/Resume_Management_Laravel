<?php

namespace App\Http\Controllers;

use App\Models\ModeOfWorkMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModeOfWorkMasterController extends Controller
{
    public function index()
    {
        $modeOfWorkList = ModeOfWorkMaster::get();
        return response()->json([
            'success' => count($modeOfWorkList) ? true : false,
            'data' => $modeOfWorkList,
        ]);
    }

    public function getAactiveModeOfWork()
    {
        $modeOfWorkList = ModeOfWorkMaster::where('status', 1)->get();
        return response()->json([
            'success' => count($modeOfWorkList) ? true : false,
            'data' => $modeOfWorkList,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mode_of_work' => 'required|string',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        }

        $validated = $validator->validated();
        $recruitmentStatus = ModeOfWorkMaster::create($validated);

        return response()->json([
            'success' => true,
            'mode_of_work' => $recruitmentStatus,
        ]);
    }

    public function update(Request $request, ModeOfWorkMaster $modeofworkmaster)
    {
        $validator = Validator::make($request->all(), [
            'mode_of_work' => 'required|string',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" =>  $validator->messages()
            ], 400);
        } else if ($modeofworkmaster->id) {

            $validated = $validator->validated();

            $modeofworkmaster->update($validated);

            return response()->json([
                'success' => true,
                'mode_of_work' => $modeofworkmaster,
            ]);
        } else {
            return response()->json([
                'success' => false,

            ]);
        }
    }

    public function destroy(ModeOfWorkMaster $modeofworkmaster)
    {
        $modeofworkmaster->delete();

        $modeOfWorkList = ModeOfWorkMaster::get();

        return response()->json([
            'success' => true,
            'data' => $modeOfWorkList,
        ]);
    }
}

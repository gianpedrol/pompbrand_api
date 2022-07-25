<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }

        /* 1 = Administrador | 2 = Usuario */
        if (auth()->user()->role_id != 1) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
    }

    public function createStage(Request $request)
    {
        $data = $request->only('nameStage', 'phaseID');
        try {
            \DB::beginTransaction();

            $createStage = new Stage();
            $createStage->stage = $data['nameStage'];
            $createStage->phase_id = $data['phaseID'];
            $createStage->save();


            \DB::commit();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            \DB::rollback();
            return ['error' => 'Could not write data', 400];
        }

        return response()->json(['status' => 'success', 'Created Stage Successfully', $createStage], 200);
    }

    public function listStages()
    {
        $phases = Phase::from('table_phases as phase')
            ->select('phase.phase_name', 'stage.id as stageID', 'stage.stage')
            ->join('table_stages as stage', 'stage.phase_id', '=', 'phase.id')
            ->get();

        return response()->json($phases, 200);
    }

    public function updateStage(Request $request, $id)
    {
        $data = $request->only('name', 'phaseID');
        $stage = Stage::where('id', $id)->first();

        try {
            \DB::beginTransaction();
            $stage->stage = $data['name'];
            $stage->phase_id = $data['phaseID'];
            $stage->update();
            \DB::commit();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            \DB::rollback();
            return ['error' => 'Could not write data', 400];
        }

        return response()->json($stage, 200);
    }
    public function deleteStage($id)
    {
        try {
            \DB::beginTransaction();

            $stage = Stage::where('id', $id)->first();

            if (!empty($stage)) {
                Stage::findOrFail($id)->delete();
                return response()->json(['message' => 'Stage successfully deleted'], 200);
            } else {
                return response()->json(['error' => 'cant found the phase'], 404);
            }


            \DB::commit();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            \DB::rollback();
            return ['error' => 'Could not write data', 400];
        }
    }
}

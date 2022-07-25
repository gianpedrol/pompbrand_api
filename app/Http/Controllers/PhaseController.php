<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use App\Models\Stage;
use Illuminate\Http\Request;

class PhaseController extends Controller
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

    public function createPhase(Request $request)
    {
        $data = $request->only('name');
        try {
            \DB::beginTransaction();

            $createPhase = new Phase();
            $createPhase->phase_name = $data['name'];
            $createPhase->save();


            \DB::commit();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            \DB::rollback();
            return ['error' => 'Could not write data', 400];
        }
        return response()->json(['status' => 'success', 'Created Phase Successfully', $createPhase], 200);
    }

    public function listPhases()
    {
        $phases = Phase::all();

        foreach ($phases as $key => $stage) {
            $stage['stages'] = Stage::from('table_stages as stage')
                ->select('stage.stage')
                ->join('table_phases as phase', 'phase.id', '=', 'stage.phase_id')
                ->where('stage.phase_id', $stage['id'])
                ->get();
        }

        $phases = $phases->toArray();

        return response()->json($phases, 200);
    }

    public function updatePhase(Request $request, $id)
    {
        $data = $request->only('name');
        $phases = Phase::where('id', $id)->first();

        try {
            \DB::beginTransaction();
            $phases->phase_name = $data['name'];
            $phases->update();
            \DB::commit();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            \DB::rollback();
            return ['error' => 'Could not write data', 400];
        }

        return response()->json($phases, 200);
    }

    public function deletePhase($id)
    {
        try {
            \DB::beginTransaction();

            $phase = Phase::where('id', $id)->first();

            if (!empty($phase)) {
                Phase::findOrFail($id)->delete();
                return response()->json(['message' => 'Phase successfully deleted'], 200);
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

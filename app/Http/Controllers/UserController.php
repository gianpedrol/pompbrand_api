<?php

namespace App\Http\Controllers;

use App\Models\ClientStage;
use App\Models\Phase;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }

    }
    public function createUser(Request $request){
        $data = $request->only('name', 'cpf', 'phone', 'email');

        $user = User::where('email', $data['email'])->first();

      /*  if (!empty($user)) {
            return response()->json(['error' =>"User already exists!"], 400);
        }*/

        $phases = Phase::all();
        $stages = Stage::all();
       // dd($stages);
        try {
            \DB::beginTransaction();

            //Define nivel user Senne
            $role_id = 2;

            //$senha_md5= Str::random(8);//Descomentar após testes
            $senha_md5 = '654321';
            $senha_temp = bcrypt($senha_md5);

            $newUser = new User();
            $newUser->name = $data['name'];
            $newUser->email = $data['email'];
            $newUser->cpf = $data['cpf'];
            $newUser->phone = $data['phone'];
            $newUser->status = 1;
            $newUser->role_id = $role_id;
            $newUser->password = $senha_temp;
            $newUser->save();

                foreach ($stages as $stage){
                 ClientStage::create(['id_user'=> $newUser->id, 'id_phase' =>  $stage['phase_id'], 'id_stage' => $stage['id']]);
                }


            \DB::commit();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            \DB::rollback();
            return ['error' => 'Could not write data', 400];
        }

        return response()->json([
            'message' => "User registered successfully!", 'data' => $newUser
        ], 200);

    }

    public function listUsers(){
        $users = User::all();

        foreach ($users as $user){
            
            $user['stage'] = ClientStage::from('client_phases as clientphase')
            ->select('phases.phase_name', 'stages.id as StageID','stages.stage', 'clientphase.status as StageStatus')
            ->leftJoin('stages', 'stages.id', '=', 'clientphase.id_stage')
            ->leftJoin('phases', 'phases.id', '=', 'stages.phase_id')
            ->where('clientphase.id_user', '=', $user->id)
            ->orderBy('clientphase.updated_at', 'DESC')
            ->first();
        }


        return response()->json([
            'message' => "list users!", 'data' => $users
        ], 200);

    }
    public function updateUser(Request $request){

    }
    public function showUser(Request $request){

    }


    public function deleteUser(Request $request){

    }
}
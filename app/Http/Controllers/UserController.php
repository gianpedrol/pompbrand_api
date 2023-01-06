<?php

namespace App\Http\Controllers;

use App\Mail\emailPassword;
use App\Models\ClientStage;
use App\Models\Documents;
use App\Models\Finance;
use App\Models\Phase;
use App\Models\Stage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use File;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
    }
    public function createUser(Request $request)
    {
        $data = $request->only('name', 'cpf', 'phone', 'email');

        $user = User::where('email', $data['email'])->first();

        if (!empty($user)) {
            return response()->json(['error' => "User already exists!"], 400);
        }

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

            foreach ($stages as $stage) {
                ClientStage::create(['id_user' => $newUser->id, 'id_phase' =>  $stage['phase_id'], 'id_stage' => $stage['id']]);
            }


            \DB::commit();

            try {
                /* Enviar e-mail para o usuário com sua senha de acesso */
                Mail::to($newUser->email)->send(new emailPassword($data, $senha_md5));
            } catch (Exception $ex) {
                return response()->json(['error' => 'Não foi possível enviar', $ex], 500);
            }
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            \DB::rollback();
            return ['error' => 'Could not write data', $th->getMessage(), 400];
        }

        return response()->json([
            'message' => "User registered successfully!", 'data' => $newUser
        ], 200);
    }

    public function listUsers()
    {
        $users = User::all();

        foreach ($users as $user) {

            $user['stage'] = ClientStage::from('client_phases as clientphase')
                ->select('phases.phase_name', 'stages.id as StageID', 'stages.stage', 'clientphase.status as StageStatus')
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
    public function updateUser($id, Request $request)
    {
        $data = $request->only('name', 'phone', 'cpf', 'email');

        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'error' => "user not found!"
            ], 404);
        }
        try {
            \DB::beginTransaction();

            $user->update($data);

            \DB::commit();
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            \DB::rollback();
            return ['error' => 'Não foi possivel salvar no banco de dados', 'erro' => $th->getMessage(), 400];
        }


        return response()->json(['message' => 'Usuário atualizado com sucesso!']);
    }
    public function showUser(Request $request, $id)
    {

        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'error' => "user not found!"
            ], 404);
        }

        $phases = Phase::all();


        foreach ($phases as $key => $phase) {
            //  $item['Fase'] = Phase::where('id', $phase->id)->get(); 

            $phase['etapas'] = ClientStage::from('client_phases as clientphase')
                ->select('stages.stage', 'clientphase.status', 'clientphase.id as ParentID')
                ->leftJoin('stages', 'stages.id', '=', 'clientphase.id_stage')
                ->leftJoin('phases', 'phases.id', '=', 'stages.phase_id')
                ->where('clientphase.id_user', '=', $user->id)
                ->where('phases.id', '=', $phase->id)
                ->orderBy('clientphase.updated_at', 'DESC')
                ->get();
        }


        if ($user) {
            return response()->json([
                'message' => "list users!", 'data' => $user, 'Fases' => $phases
            ], 200);
        }
    }


    public function deleteUser(Request $request)
    {
        if ($request->user()->role_id != 1) {
            return response()->json(['error' => "Não Autorizado"], 401);
        }

        $id = $request->id;
        $userCheck = User::where('id', $id)->first();

        if (empty($userCheck)) {
            return response()->json(['message' => 'Não foi possível encontrar o usuário'], 404);
        }

        try {
            $user = User::findOrFail($id)->delete();

            if (!$user) {
                return response()->json(['message' => 'Não foi possível deletar o usuário'], 500);
            }
            return response()->json(['message' => 'Usuário deletado'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Não foi possível deletar o usuário', $e], 400);
        }
    }

    public function updateUserStage(Request $request)
    {

        $data = $request->only('stageID', 'status', 'userID');

        $updateStatus = ClientStage::where('id', $data['stageID'])->first();

        try {
            \DB::beginTransaction();

            if ($updateStatus && $data['status'] <= 1 && $data['status'] >= 0) {
                ClientStage::where('id', $data['stageID'])->update(['status' => $data['status']]);
            }
            \DB::commit();
        } catch (\Throwable $th) {

            \DB::rollback();
            return ['error' => 'Could not write data', $th->getMessage(), 400];
        }

        return response()->json(['message' => "Success"], 200);
    }

    public function updateDocsUser(Request $request)
    {
        if ($request->user()->role_id != 1) {
            if (!$request->user()->permission_user($request->user()->id, 1)) {
                return response()->json(['message' => "Não Autorizado"], 401);
            }
        }



        $filename = '';
        $user = User::where('id', $request->id_user)->first();

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $file_name = $file->getClientOriginalName();
            $file_path = 'uploads/docs/';

            $file->move($file_path, $file_name);

            if ($request->hasFile('file') != "") {
                $filename = $file_name;

                try {

                    $newDocument = new Documents();
                    $newDocument->user_id = $request->userID;
                    $newDocument->document  = $filename;
                    $newDocument->save();
                } catch (\Throwable $th) {

                    \DB::rollback();
                    return ['error' => 'Could not write data', $th->getMessage(), 400];
                }
            }

            return response()->json(
                ['status' => 'success', 'Arquivo enviado com sucesso!'],
                200
            );
        }
    }

    public function getDocsUser($id)
    {
        $documents = Documents::where('user_id', $id)->get();

        $data = [];
        foreach ($documents as $item) {
            $data[] = [
                'file' => config('app.url') . 'uploads/docs/' . $item->document,
                'id' => $item->id
            ];
        }
        return response()->json(
            ['status' => 'success', $data],
            200
        );
    }

    public function deleteDocument(Request $request, $id)
    {
        if ($request->user()->role_id != 1) {
            return response()->json(['error' => "Não Autorizado"], 401);
        }

        $documents = Documents::where('id', $id)->first();

        if (empty($documents)) {
            return response()->json(['message' => 'Não foi possível encontrar o documento'], 404);
        }


        try {
            $pathDoc = 'uploads/docs/' . $documents->document;
            $doc = File::delete($pathDoc);;
            $document = Documents::findOrFail($id)->delete();

            if (!$document && !$doc) {
                return response()->json(['message' => 'Não foi possível deletar o Documento'], 500);
            }
            return response()->json(['message' => 'Documento deletado'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Não foi possível deletar o Documento', $e], 400);
        }
    }

    public function uploadFinanceUser(Request $request)
    {
        if ($request->user()->role_id != 1) {
            if (!$request->user()->permission_user($request->user()->id, 1)) {
                return response()->json(['message' => "Não Autorizado"], 401);
            }
        }

        $filename = '';
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $file_name = $file->getClientOriginalName();
            $file_path = 'uploads/finances/';

            $file->move($file_path, $file_name);

            if ($request->hasFile('file') != "") {
                $filename = $file_name;

                try {

                    $newDocument = new Finance();
                    $newDocument->user_id = $request->userID;
                    $newDocument->document  = $filename;
                    $newDocument->save();
                } catch (\Throwable $th) {

                    \DB::rollback();
                    return ['error' => 'Could not write data', $th->getMessage(), 400];
                }
            }

            return response()->json(
                ['status' => 'success', 'Arquivo enviado com sucesso!'],
                200
            );
        }
    }

    public function getFinanceUser($id)
    {
        $finances = Finance::where('user_id', $id)->get();

        $data = [];
        foreach ($finances as $item) {

            $data[] = [
                'file' => config('app.url') . 'uploads/finances/' . $item->document,
                'id' => $item->id
            ];
        }
        return response()->json(
            ['status' => 'success', $data],
            200
        );
    }


    public function deleteFinance(Request $request, $id)
    {
        if ($request->user()->role_id != 1) {
            return response()->json(['error' => "Não Autorizado"], 401);
        }

        $finances = Finance::where('id', $id)->first();

        if (empty($finances)) {
            return response()->json(['message' => 'Não foi possível encontrar o documento'], 404);
        }


        try {
            $pathDoc = 'uploads/finances/' . $finances->document;
            $doc = File::delete($pathDoc);;
            $document = Finance::findOrFail($id)->delete();

            if (!$document && !$doc) {
                return response()->json(['message' => 'Não foi possível deletar o Documento'], 500);
            }
            return response()->json(['message' => 'Documento deletado'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Não foi possível deletar o Documento', $e], 400);
        }
    }
}

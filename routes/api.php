<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\UserController;
use Illuminate\Contracts\Auth\UserProvider;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//ROTA DE NÃO AUTORIZADO
Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->group(function () {

    /** ROTAS RELACIONADAS AS PHASES  */
    Route::post('create/phase', [PhaseController::class, 'createPhase']);
    Route::get('list/phase', [PhaseController::class, 'listPhases']);
    Route::put('edit/phase/{id}', [PhaseController::class, 'updatePhase']);
    Route::delete('delete/phase/{id}', [PhaseController::class, 'deletePhase']);

    /** ROTAS RELACIONADAS AS ETAPAS DE CADA FASE */
    Route::post('create/stage', [StageController::class, 'createStage']);
    Route::get('list/stage', [StageController::class, 'listStages']);
    Route::put('edit/stage/{id}', [StageController::class, 'updateStage']);
    Route::delete('delete/stage/{id}', [StageController::class, 'deleteStage']);

    /** ROTAS RELACIONADAS AO USUÁRIO */
    Route::post('create/user', [UserController::class, 'createUser']);
    Route::get('list/users', [UserController::class, 'listUsers']);
});

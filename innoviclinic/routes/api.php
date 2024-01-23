<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProfissaoController;
use App\Http\Controllers\Api\ProntuarioController;
use App\Http\Controllers\Api\AgendaStatusController;
use App\Http\Controllers\Api\EscolaridadeController;
use App\Http\Controllers\Api\ProcedimentoController;
use App\Http\Controllers\Api\EspecialidadeController;
use App\Http\Controllers\Api\ProcedimentoTipoController;
use App\Models\Procedimento;

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

Route::post('/login', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/novo', [AuthController::class, 'novo']);


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'empresas'], function () {
        Route::get('/', [EmpresaController::class, 'index']);
        Route::get('/{id}', [EmpresaController::class, 'show'])->middleware('check-empresa-id');
        Route::post('/', [EmpresaController::class, 'store']);
        Route::put('/{id}', [EmpresaController::class, 'update'])->middleware('check-empresa-id');
        Route::delete('/{id}', [EmpresaController::class, 'destroy'])->middleware('check-empresa-id');
    });

    Route::group(['prefix' => 'procedimentos'], function () {
        Route::get('/', [ProcedimentoController::class, 'index']);
        Route::get('/{id}', [ProcedimentoController::class, 'show'])->middleware('check-procedimento-empresa-id');
        Route::post('/', [ProcedimentoController::class, 'store']);
        Route::put('/{id}', [ProcedimentoController::class, 'update'])->middleware('check-procedimento-empresa-id');
        Route::delete('/{id}', [ProcedimentoController::class, 'destroy'])->middleware('check-procedimento-empresa-id');
    });

    Route::resources([
        'prontuarios' => ProntuarioController::class,
        'procedimento-tipos' => ProcedimentoTipoController::class,
        'escolaridades' => EscolaridadeController::class,
        'profissoes' => ProfissaoController::class,
        'agenda-status' => AgendaStatusController::class,
        'especialidades' => EspecialidadeController::class,
    ]);
});

Route::get('/', function () {
    return response()->json([
        'success' => true
    ]);
});

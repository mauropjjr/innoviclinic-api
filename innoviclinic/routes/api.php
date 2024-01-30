<?php

use App\Models\Procedimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\ConvenioController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProfissaoController;
use App\Http\Controllers\Api\ProntuarioController;
use App\Http\Controllers\Api\SecretariaController;
use App\Http\Controllers\Api\AgendaStatusController;
use App\Http\Controllers\Api\EscolaridadeController;
use App\Http\Controllers\Api\ProcedimentoController;
use App\Http\Controllers\Api\ProfissionalController;
use App\Http\Controllers\Api\EspecialidadeController;
use App\Http\Controllers\Api\ProcedimentoTipoController;

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

    Route::group(['prefix' => 'convenios'], function () {
        Route::get('/', [ConvenioController::class, 'index']);
        Route::get('/{id}', [ConvenioController::class, 'show'])->middleware('check-convenio-empresa-id');
        Route::post('/', [ConvenioController::class, 'store']);
        Route::put('/{id}', [ConvenioController::class, 'update'])->middleware('check-convenio-empresa-id');
        Route::delete('/{id}', [ConvenioController::class, 'destroy'])->middleware('check-convenio-empresa-id');
    });

    Route::group(['prefix' => 'secretarias'], function () {
        Route::get('/', [SecretariaController::class, 'index']);
        Route::get('/{id}', [SecretariaController::class, 'show'])->middleware('check-profissional-secretaria-id');
        Route::post('/', [SecretariaController::class, 'store']);
        Route::put('/{id}', [SecretariaController::class, 'update'])->middleware('check-profissional-secretaria-id');
        Route::delete('/{id}', [SecretariaController::class, 'destroy'])->middleware('check-profissional-secretaria-id');
    });

    Route::group(['prefix' => 'profissionais'], function () {
        Route::get('/', [ProfissionalController::class, 'index']);
        Route::get('/{id}', [ProfissionalController::class, 'show'])->middleware('check-empresa-profissional-id');
        Route::post('/', [ProfissionalController::class, 'store']);
        Route::put('/{id}', [ProfissionalController::class, 'update'])->middleware('check-empresa-profissional-id');
        Route::delete('/{id}', [ProfissionalController::class, 'destroy'])->middleware('check-empresa-profissional-id');
    });

    Route::group(['prefix' => 'pacientes'], function () {
        Route::get('/search', [PacienteController::class, 'searchByCpfOrName']);
        Route::get('/', [PacienteController::class, 'index']);
        Route::get('/{id}', [PacienteController::class, 'show'])->middleware('check-prontuario-paciente-id');
        Route::post('/', [PacienteController::class, 'store']);
        Route::put('/{id}', [PacienteController::class, 'update'])->middleware('check-prontuario-paciente-id');
        Route::delete('/{id}', [PacienteController::class, 'destroy'])->middleware('check-prontuario-paciente-id');
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

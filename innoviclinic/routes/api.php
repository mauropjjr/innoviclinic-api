<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\FeriadoController;
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
use App\Http\Controllers\Api\ProfissionalAgendaController;
use App\Http\Controllers\Api\EmpresaConfiguracaoController;
use App\Http\Controllers\Api\EmpresaProfissionalController;
use App\Http\Controllers\Api\InteracaoAtendimentoController;
use App\Http\Controllers\Api\InteracaoController;
use App\Http\Controllers\Api\ProfissionalSecretariaController;
use App\Http\Controllers\Api\SalaController;
use App\Http\Controllers\Api\SecaoController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\AgendaTipoController;
use App\Http\Controllers\Api\RecoveryPasswordController;
use App\Http\Public\AgendaPublic;
use App\Http\Public\EmpresaPublic;
use App\Http\Public\PacientePublic;
use App\Http\Public\ProfissionalPublic;

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
Route::post('/register', [AuthController::class, 'register']);


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

    Route::group(['prefix' => 'empresa-configuracao'], function () {
        Route::get('/', [EmpresaConfiguracaoController::class, 'index']);
        Route::get('/{id}', [EmpresaConfiguracaoController::class, 'show'])->middleware('check-empresa-configuracao-empresa-id');
        Route::post('/', [EmpresaConfiguracaoController::class, 'store']);
        Route::put('/{id}', [EmpresaConfiguracaoController::class, 'update'])->middleware('check-empresa-configuracao-empresa-id');
        Route::delete('/{id}', [EmpresaConfiguracaoController::class, 'destroy'])->middleware('check-empresa-configuracao-empresa-id');
    });

    Route::group(['prefix' => 'empresa-profissionais'], function () {
        Route::get('/', [EmpresaProfissionalController::class, 'index']);
        Route::get('/{id}', [EmpresaProfissionalController::class, 'show'])->middleware('check-empresa-profissional-empresa-id');
        Route::post('/', [EmpresaProfissionalController::class, 'store']);
        Route::put('/{id}', [EmpresaProfissionalController::class, 'update'])->middleware('check-empresa-profissional-empresa-id');
        Route::delete('/{id}', [EmpresaProfissionalController::class, 'destroy'])->middleware('check-empresa-profissional-empresa-id');
    });

    Route::group(['prefix' => 'profissional-secretarias'], function () {
        Route::get('/', [ProfissionalSecretariaController::class, 'index']);
        Route::get('/{id}', [ProfissionalSecretariaController::class, 'show'])->middleware('check-profissional-id-secretaria-empresa-id');
        Route::post('/', [ProfissionalSecretariaController::class, 'store']);
        Route::put('/{id}', [ProfissionalSecretariaController::class, 'update'])->middleware('check-profissional-id-secretaria-empresa-id');
        Route::delete('/{id}', [ProfissionalSecretariaController::class, 'destroy'])->middleware('check-profissional-id-secretaria-empresa-id');
    });

    Route::group(['prefix' => 'profissional-agendas'], function () {
        Route::get('/', [ProfissionalAgendaController::class, 'index']);
        Route::get('/{id}', [ProfissionalAgendaController::class, 'show'])->middleware('check-profissional-id-agenda-empresa-id');
        Route::post('/', [ProfissionalAgendaController::class, 'store']);
        Route::put('/{id}', [ProfissionalAgendaController::class, 'update'])->middleware('check-profissional-id-agenda-empresa-id');
        Route::delete('/{id}', [ProfissionalAgendaController::class, 'destroy'])->middleware('check-profissional-id-agenda-empresa-id');
    });

    Route::group(['prefix' => 'secoes'], function () {
        Route::get('/', [SecaoController::class, 'index']);
        Route::get('/{id}', [SecaoController::class, 'show'])->middleware('check-secao-profissional-id-empresa-id');
        Route::post('/', [SecaoController::class, 'store']);
        Route::put('/{id}', [SecaoController::class, 'update'])->middleware('check-secao-profissional-id-empresa-id');
        Route::delete('/{id}', [SecaoController::class, 'destroy'])->middleware('check-secao-profissional-id-empresa-id');
    });

    Route::group(['prefix' => 'interacoes'], function () {
        Route::get('/', [InteracaoController::class, 'index']);
        Route::get('/{id}', [InteracaoController::class, 'show'])->middleware('check-interacao-profissional-id-empresa-id');
        Route::post('/', [InteracaoController::class, 'store']);
        Route::put('/{id}', [InteracaoController::class, 'update'])->middleware(['check-interacao-profissional-id-empresa-id', 'check-interacao-finalizada']);
        Route::delete('/{id}', [InteracaoController::class, 'destroy'])->middleware('check-interacao-profissional-id-empresa-id');
    });

    Route::group(['prefix' => 'interacao-atendimentos'], function () {
        Route::get('/', [InteracaoAtendimentoController::class, 'index']);
        Route::get('/{id}', [InteracaoAtendimentoController::class, 'show'])->middleware('check-interacao-atendimento-profissional-id-empresa-id');
        Route::post('/', [InteracaoAtendimentoController::class, 'store']);
        Route::put('/{id}', [InteracaoAtendimentoController::class, 'update'])->middleware(['check-interacao-atendimento-profissional-id-empresa-id', 'check-interacao-atendimento-finalizada']);
        Route::delete('/{id}', [InteracaoAtendimentoController::class, 'destroy'])->middleware('check-interacao-atendimento-profissional-id-empresa-id');
    });

    Route::group(['prefix' => 'agendas'], function () {
        Route::get('/', [AgendaController::class, 'index'])->middleware('check-agenda-profissional-id-empresa-id');
        Route::get('/pacientes', [AgendaController::class, 'listAppointments'])->middleware('check-agenda-profissional-id-empresa-id');
        Route::get('/{id}', [AgendaController::class, 'show'])->name('agenda.show')->middleware('check-agenda-profissional-id-empresa-id');
        Route::post('/', [AgendaController::class, 'store'])->middleware('check-agenda-profissional-id-empresa-id');
        Route::put('/{id}', [AgendaController::class, 'update'])->middleware('check-agenda-profissional-id-empresa-id');
        Route::delete('/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy')->middleware('check-agenda-profissional-id-empresa-id');
    });

    Route::group(['prefix' => 'procedimento-tipos'], function () {
        Route::get('/', [ProcedimentoTipoController::class, 'index']);
        Route::get('/{id}', [ProcedimentoTipoController::class, 'show'])->middleware('check-procedimento-tipo-empresa-id');
        Route::post('/', [ProcedimentoTipoController::class, 'store']);
        Route::put('/{id}', [ProcedimentoTipoController::class, 'update'])->middleware('check-procedimento-tipo-empresa-id');
        Route::delete('/{id}', [ProcedimentoTipoController::class, 'destroy'])->middleware('check-procedimento-tipo-empresa-id');
    });

    Route::group(['prefix' => 'procedimentos'], function () {
        Route::get('/', [ProcedimentoController::class, 'index']);
        Route::get('/{id}', [ProcedimentoController::class, 'show'])->middleware('check-procedimento-empresa-id');
        Route::post('/', [ProcedimentoController::class, 'store']);
        Route::put('/{id}', [ProcedimentoController::class, 'update'])->middleware('check-procedimento-empresa-id');
        Route::delete('/{id}', [ProcedimentoController::class, 'destroy'])->middleware('check-procedimento-empresa-id');
    });

    Route::group(['prefix' => 'salas'], function () {
        Route::get('/', [SalaController::class, 'index']);
        Route::get('/{id}', [SalaController::class, 'show'])->middleware('check-sala-empresa-id');
        Route::post('/', [SalaController::class, 'store']);
        Route::put('/{id}', [SalaController::class, 'update'])->middleware('check-sala-empresa-id');
        Route::delete('/{id}', [SalaController::class, 'destroy'])->middleware('check-sala-empresa-id');
    });

    Route::group(['prefix' => 'agenda-tipos'], function () {
        Route::get('/', [AgendaTipoController::class, 'index']);
        Route::get('/{id}', [AgendaTipoController::class, 'show'])->middleware('check-agenda-tipo-empresa-id');
        Route::post('/', [AgendaTipoController::class, 'store']);
        Route::put('/{id}', [AgendaTipoController::class, 'update'])->middleware('check-agenda-tipo-empresa-id');
        Route::delete('/{id}', [AgendaTipoController::class, 'destroy'])->middleware('check-agenda-tipo-empresa-id');
    });

    Route::group(['prefix' => 'feriados'], function () {
        Route::get('/', [FeriadoController::class, 'index']);
        Route::get('/{id}', [FeriadoController::class, 'show'])->middleware('check-feriado-empresa-id');
        Route::post('/', [FeriadoController::class, 'store']);
        Route::put('/{id}', [FeriadoController::class, 'update'])->middleware('check-feriado-empresa-id');
        Route::delete('/{id}', [FeriadoController::class, 'destroy'])->middleware('check-feriado-empresa-id');
    });

    Route::group(['prefix' => 'eventos'], function () {
        Route::get('/', [EventoController::class, 'index']);
        Route::get('/{id}', [EventoController::class, 'show'])->middleware('check-evento-empresa-id');
        Route::post('/', [EventoController::class, 'store']);
        Route::put('/{id}', [EventoController::class, 'update'])->middleware('check-evento-empresa-id');
        Route::delete('/{id}', [EventoController::class, 'destroy'])->middleware('check-evento-empresa-id');
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
        Route::get('/', [ProfissionalController::class, 'index'])->middleware('check-empresa-profissional-id');
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
        'escolaridades' => EscolaridadeController::class,
        'profissoes' => ProfissaoController::class,
        'agenda-status' => AgendaStatusController::class,
        'especialidades' => EspecialidadeController::class,
    ]);

});

//metodos publicos
Route::group(['prefix' => 'public'], function () {
    Route::get("/empresas/{id}", [EmpresaPublic::class, "index"]);
    Route::post("/agendas", [AgendaPublic::class, "store"]);
    Route::get("/agendas/cancelar", [AgendaPublic::class, "cancelar"]);
    Route::get('/profissionais/{profissional_id}/agendas', [ProfissionalPublic::class, "getAgendas"]);
    Route::get('/profissionais/{profissional_id}/agendas/disponiveis',[ProfissionalPublic::class, "getAgendasDisponiveis"]);
    Route::get('/pacientes/agendas', [PacientePublic::class, "getAgendas"]);
});

//recovery password


Route::post('/forgot-password', [RecoveryPasswordController::class, 'otp']);


Route::get('/', function () {

    return response()->json([
        'success' => true
    ]);
});


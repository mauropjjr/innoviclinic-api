<?php

namespace App\Http\Controllers\Api;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AgendaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAgendaRequest;

class AgendaController extends Controller
{
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
        $this->middleware('check-agenda-profissional-id-empresa-id')->only(['show', 'store', 'update', 'index', 'destroy']);
    }

    public function store(StoreAgendaRequest $request)
    {
        $request->validated();
        $data = $request->all();
        return $this->agendaService->create($data);
    }

    public function update(StoreAgendaRequest $request, string $id)
    {
        $request->validated();
        $data = $request->all();
        return $this->agendaService->update($data, $id);
    }

    public function index(Request $request)
    {
        // Validação dos parâmetros da requisição
        $request->validate([
            'profissional_id' => 'required|integer',
            //'sala_id' => 'required|integer',
            'exibir_todos_status' => 'required|boolean',
            'apenas_agenda' => 'nullable|boolean',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
        ]);

        // Chama o serviço para buscar as agendas com base nos parâmetros
        $agendas = $this->agendaService->list($request->all());

        // Retorna as agendas como resposta
        return response()->json($agendas);
    }

    public function show($id)
    {
        $objeto = Agenda::with([
            'agenda_tipo:id,nome,cor,sem_horario,sem_procedimento',
            'agenda_status:id,nome',
            'profissional:id,nome',
            'sala:id,nome', 'paciente:id,nome',
            'convenio:id,nome',
            'agenda_procedimentos',
            'agenda_procedimentos.procedimento:id,nome'
        ])
            ->find($id);

        if (!$objeto) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($objeto);
    }

    public function destroy(string $id)
    {
        if (!$objeto = Agenda::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}

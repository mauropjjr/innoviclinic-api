<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AgendaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAgendaRequest;

class AgendaController extends Controller
{
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
        $this->middleware('check-agenda-profissional-id-empresa-id')->only(['store', 'update', 'index']);
    }

    public function store(StoreAgendaRequest $request)
    {
        $input = $request->validated();
        return $this->agendaService->create($input);
    }

    public function update(StoreAgendaRequest $request, string $id)
    {
        $input = $request->validated();
        return $this->agendaService->update($input, $id);
    }

    public function index(Request $request)
    {
        // Validação dos parâmetros da requisição
        $request->validate([
            'profissional_id' => 'required|integer',
            'sala_id' => 'required|integer',
            'exibir_todos_status' => 'required|boolean',
            'visao' => 'required|string|in:mes,semanal,dia,lista',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
        ]);

        // Chama o serviço para buscar as agendas com base nos parâmetros
        $agendas = $this->agendaService->list($request->all());

        // Retorna as agendas como resposta
        return response()->json($agendas);
    }

}

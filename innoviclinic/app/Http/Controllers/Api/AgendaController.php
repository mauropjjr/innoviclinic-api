<?php

namespace App\Http\Controllers\Api;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AgendaService;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAgendaRequest;

class AgendaController extends Controller
{
    protected $agendaService;
    protected $customAuth;

    public function __construct(AgendaService $agendaService, CustomAuthService $customAuth)
    {
        $this->agendaService = $agendaService;
        $this->customAuth = $customAuth;
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

    public function listAppointments(Request $request)
    {
        // Validação dos parâmetros da requisição
        $request->validate([
            'profissional_id' => 'required|integer',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
        ]);

        $user = $this->customAuth->getUser();
        $query = Agenda::query();

        // Filtrar os agendamentos apenas da empresa do usuário logado
        $query->where('agendas.empresa_id', $user->empresa_profissional->empresa_id)
            ->where('agendas.profissional_id', $request->input('profissional_id'));

        // Aplicar filtros adicionais baseados nos parâmetros do request
        if ($request->filled('sala_id')) {
            $query->where('agendas.sala_id', $request->input('sala_id'));
        }
        if ($request->filled('nome_agenda')) {
            $query->where('agendas.nome', 'LIKE', "%" . $request->input('nome_agenda') . "%");
        }
        if ($request->filled('data_inicio')) {
            $query->where('agendas.data', '>=', $request->input('data_inicio'));
        }
        if ($request->filled('data_fim')) {
            $query->where('agendas.data', '<=', $request->input('data_fim'));
        }

        // Carregar as relações corretamente, incluindo prontuário como um objeto relacionado
        $query->with([
            'agenda_tipo',
            'agenda_status',
            'sala',
            'profissional',
            'prontuarios' => function ($query) use ($user, $request) {
                $query->where('empresa_id', $user->empresa_profissional->empresa_id)
                    ->where('profissional_id', $request->input('profissional_id'));
            }
        ]);

        //echo $query->toSql();

        // Retornar os resultados paginados com o objeto prontuário incluído
        return response()->json($query->paginate($request->input('per_page') ?? 15));
    }

    public function show($id)
    {
        $objeto = Agenda::with([
            'agenda_tipo:id,nome,cor,sem_horario,sem_procedimento',
            'agenda_status:id,nome',
            'profissional:id,nome',
            'sala:id,nome',
            'paciente:id,nome',
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

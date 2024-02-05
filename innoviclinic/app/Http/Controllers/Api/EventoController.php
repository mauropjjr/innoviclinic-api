<?php

namespace App\Http\Controllers\Api;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEventoRequest;

class EventoController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-evento-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
{
    $user = $this->customAuth->getUser();
    $query = Evento::query()->with(['evento_profissionais' => function ($query) {
        $query->select('id', 'evento_id', 'profissional_id')
              ->with('pessoa:id,nome,email');
    }]);

    $query->where('empresa_id', $user->empresa_profissional->empresa_id);

    if ($request->has('nome') && $request->input('nome')) {
        $query->where('nome', 'LIKE', "%" . $request->input('nome') . "%");
    }

    return response()->json($query->get());
}


    public function show($id)
    {
        $objeto = Evento::with([
            'evento_profissionais' => function ($query) {
                $query->select('id', 'evento_id', 'profissional_id')
                ->with('pessoa:id,nome,email');
            }
        ])->find($id);

        if (!$objeto) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($objeto);
    }

    public function store(StoreEventoRequest  $request)
    {
        $input = $request->validated();

        return DB::transaction(function () use ($input, $request) {
            $user = $this->customAuth->getUser();

            // Criação do evento
            $evento = Evento::create($input);

            // Armazena os profissionais
            $evento->profissionais()->attach($request->input('profissionais'), ['usuario_id' => $user->id, 'created_at' => now()]);

            return response()->json($evento);
        });
    }

    public function update(StoreEventoRequest $request, string $id)
    {
        $input = $request->validated();

        return DB::transaction(function () use ($request, $input, $id) {
            $user = $this->customAuth->getUser();

            // Atualização do evento
            $pessoa = Evento::findOrFail($id);
            $pessoa->update($input);

            // Sincroniza os profissionais
            $pessoa->profissionais()->syncWithPivotValues($request->input('profissionais'), ['usuario_id' => $user->id, 'created_at' => now()]);

            return response()->json($pessoa);
        });
    }

    public function destroy(string $id)
    {
        if (!$objeto = Evento::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}

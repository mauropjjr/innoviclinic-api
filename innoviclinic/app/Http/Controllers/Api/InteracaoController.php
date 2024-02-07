<?php

namespace App\Http\Controllers\Api;

use App\Models\Interacao;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreInteracaoRequest;

class InteracaoController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-interacao-profissional-id-empresa-id')->only(['show', 'update', 'destroy']);
        $this->middleware('check-interacao-finalizada')->only(['update']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        // Verifica se o parâmetro foi informado
        if (!$request->filled('prontuario_id')) {
            return response()->json(['error' => 'É necessário informar o prontuario_id do paciente.'], Response::HTTP_BAD_REQUEST);
        }

        $query = Interacao::with('interacao_atendimentos')
        ->whereHas('prontuario', function ($query) use ($user) {
            $query->where('empresa_id', $user->empresa_profissional->empresa_id)
                ->where('profissional_id', $user->empresa_profissional->profissional_id);
        });

    return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = Interacao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreInteracaoRequest  $request)
    {
        $input = $request->validated();
        $objeto = Interacao::create($input);
        return response()->json($objeto);
    }

    public function update(StoreInteracaoRequest $request, string $id)
    {
        if (!$objeto = Interacao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $input = $request->validated();
        $objeto->update($input);
        return response()->json($objeto);
    }

    public function destroy(string $id)
    {
        if (!$objeto = Interacao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Interacao;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreInteracaoAtendimentoRequest;
use App\Models\InteracaoAtendimento;

class InteracaoAtendimentoController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-interacao-atendimento-profissional-id-empresa-id')->only(['show', 'update', 'destroy']);
        $this->middleware('check-interacao-atendimento-finalizada')->only(['update']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        // Verifica se o parâmetro foi informado
        if (!$request->filled('interacao_id')) {
            return response()->json(['error' => 'É necessário informar o interacao_id do atendimento.'], Response::HTTP_BAD_REQUEST);
        }

        $query = InteracaoAtendimento::where('interacao_id', $request->input('interacao_id'))
            ->with(['interacao' => function ($query) use ($user) {
                $query->with(['prontuario' => function ($query) use ($user) {
                    $query->where('profissional_id', $user->empresa_profissional->profissional_id)
                        ->where('empresa_id', $user->empresa_profissional->empresa_id);
                }]);
            }]);

        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = InteracaoAtendimento::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreInteracaoAtendimentoRequest  $request)
    {
        $input = $request->validated();
        $objeto = InteracaoAtendimento::create($input);
        return response()->json($objeto);
    }

    public function update(StoreInteracaoAtendimentoRequest $request, string $id)
    {
        if (!$objeto = InteracaoAtendimento::find($id)) {
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
        if (!$objeto = InteracaoAtendimento::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}

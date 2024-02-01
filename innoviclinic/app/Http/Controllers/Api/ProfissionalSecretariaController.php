<?php

namespace App\Http\Controllers\Api;

use App\Models\ProfissionalSecretaria;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProfissionalSecretariaRequest;

class ProfissionalSecretariaController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-profissionalid-secretaria-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = ProfissionalSecretaria::query()->with(['secretaria']);
        $query->where('empresa_id', $user->empresa_profissional->empresa_id);
        $query->where('profissional_id', $user->empresa_profissional->profissional_id);

        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = ProfissionalSecretaria::with(['secretaria'])->find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreProfissionalSecretariaRequest  $request)
    {
        $input = $request->validated();
        $objeto = ProfissionalSecretaria::create($input);
        return response()->json($objeto);
    }

    public function update(StoreProfissionalSecretariaRequest $request, string $id)
    {
        if (!$objeto = ProfissionalSecretaria::find($id)) {
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
        if (!$objeto = ProfissionalSecretaria::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}

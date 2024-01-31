<?php

namespace App\Http\Controllers\Api;

use App\Models\EmpresaConfiguracao;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEmpresaConfiguracaoRequest;

class EmpresaConfiguracaoController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-empresa-configuracao-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = EmpresaConfiguracao::query();
        $query->where('empresa_id', $user->empresa_profissional->empresa_id);

        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = EmpresaConfiguracao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreEmpresaConfiguracaoRequest  $request)
    {
        $input = $request->validated();
        $objeto = EmpresaConfiguracao::create($input);
        return response()->json($objeto);
    }

    public function update(StoreEmpresaConfiguracaoRequest $request, string $id)
    {
        if (!$objeto = EmpresaConfiguracao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $objeto->update($request->all());
        return response()->json($objeto);
    }

    public function destroy(string $id)
    {
        if (!$objeto = EmpresaConfiguracao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\EmpresaProfissional;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEmpresaProfissionalRequest;

class EmpresaProfissionalController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-empresa-profissional-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = EmpresaProfissional::query()->with(['profissional']);
        $query->where('empresa_id', $user->empresa_profissional->empresa_id);

        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = EmpresaProfissional::with(['profissional'])
        ->leftJoin('pessoas', 'empresa_profissionais.profissional_id', '=', 'pessoas.id')
        ->leftJoin('profissionais', 'empresa_profissionais.profissional_id', '=', 'profissionais.pessoa_id')
        
        ->find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
      
        return response()->json($objeto);
    }

    public function store(StoreEmpresaProfissionalRequest  $request)
    {
        $input = $request->validated();
        $objeto = EmpresaProfissional::create($input);
        return response()->json($objeto);
    }

    public function update(StoreEmpresaProfissionalRequest $request, string $id)
    {
        if (!$objeto = EmpresaProfissional::find($id)) {
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
        if (!$objeto = EmpresaProfissional::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}

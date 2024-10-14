<?php

namespace App\Http\Controllers\Api;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEmpresaRequest;
use App\Http\Requests\Api\UpdateEmpresaRequest;

class EmpresaController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;
        $this->middleware('check-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = Empresa::query();
        $query->with('profissional');

        // Adicione uma condição para listar apenas empresas com profissional_id do usuário logado
        $query->whereHas('profissional', function ($subquery) use ($user) {
            $subquery->where('profissional_id', $user->empresa_profissional->profissional_id);
        });

        if ($request->has('tipo') && $request->input('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }
        if ($request->has('nome') && $request->input('nome')) {
            $query->where('nome', 'LIKE', "%" . $request->input('nome') . "%");
        }
        if ($request->has('email') && $request->input('email')) {
            $query->where('email', $request->input('email'));
        }
        if ($request->has('telefone') && $request->input('telefone')) {
            $query->where('telefone', $request->input('telefone'));
        }
        if ($request->has('cpf_cnpj') && $request->input('cpf_cnpj')) {
            $query->where('cpf_cnpj', $request->input('cpf_cnpj'));
        }
        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = Empresa::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreEmpresaRequest $request)
    {
        $input = $request->validated();

        return DB::transaction(function () use ($input, $request) {
            // Criação da empresa
            $empresa = Empresa::create($input);
            // Associa o profissional à empresa
            $empresa->profissional()->create(['profissional_id' => $request->input('profissional_id')]);

            return response()->json($empresa);
        });
    }

    public function update(UpdateEmpresaRequest $request, string $id)
    {
        if (!$objeto = Empresa::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $objeto->update($request->all());
        return response()->json($objeto);
    }

    public function destroy(string $id)
    {
        if (!$objeto = Empresa::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function getWith(Request $request) {
        $request->validate([
            "with" => "required|array",
            "with.*" => "string"
        ]);

        $objeto = Empresa::with($request->with)->findOr($request->id, ["*"], function() {
            return response()->json([
                "error" => "Não encontrado"
            ], Response::HTTP_NOT_FOUND);
        });
// profissional.profissional.profissional_especialidades") dando pau
        return response()->json($objeto);
    }
}

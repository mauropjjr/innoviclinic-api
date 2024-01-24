<?php

namespace App\Http\Controllers\Api;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSecretariaRequest;
use App\Http\Requests\Api\UpdateSecretariaRequest;

class SecretariaController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;
        $this->middleware('check-profissional-secretaria-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = Pessoa::query();
        $query->with('profissional_secretaria')->where('tipo_usuario', 'Secretaria');

        $query->whereHas('profissional_secretaria', function ($subquery) use ($user) {
            $subquery->where('profissional_id', $user->empresa_profissional->profissional_id);
        });

        if ($request->has('nome') && $request->input('nome')) {
            $query->where('nome', 'LIKE', "%" . $request->input('nome') . "%");
        }
        if ($request->has('email') && $request->input('email')) {
            $query->where('email', $request->input('email'));
        }
        if ($request->has('telefone') && $request->input('telefone')) {
            $query->where('telefone', $request->input('telefone'));
        }
        if ($request->has('cpf') && $request->input('cpf')) {
            $query->where('cpf', $request->input('cpf'));
        }
        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        $objeto = Pessoa::with(['profissional_secretaria'])->find($id);
        if (!$objeto) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($objeto);
    }

    public function store(StoreSecretariaRequest $request)
    {
        $input = $request->validated();
        $input['tipo_usuario'] = 'Secretaria';

        return DB::transaction(function () use ($input, $request) {
            // Criação da secretaria
            $secretaria = Pessoa::create($input);

            // Associa o profissional à secretaria
            $user = $this->customAuth->getUser();
            $secretaria->profissional_secretaria()->create([
                'empresa_id' => $user->empresa_profissional->empresa_id,
                'profissional_id' => $user->empresa_profissional->profissional_id
            ]);

            return response()->json($secretaria);
        });
    }

    public function update(UpdateSecretariaRequest $request, string $id)
    {
        $input = $request->validated();
        $input['tipo_usuario'] = 'Secretaria';

        return DB::transaction(function () use ($request, $input, $id) {
            $user = $this->customAuth->getUser();

            // Atualização da secretaria
            $secretaria = Pessoa::findOrFail($id);
            $secretaria->update($input);

            // Associação do profissional à secretaria (se necessário)
            if (!$secretaria->profissional_secretaria) {
                $secretaria->profissional_secretaria()->create([
                    'empresa_id' => $user->empresa_profissional->empresa_id,
                    'profissional_id' => $user->empresa_profissional->profissional_id
                ]);
            }

            return response()->json($secretaria);
        });
    }

    public function destroy(string $id)
    {
        if (!$objeto = Pessoa::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}

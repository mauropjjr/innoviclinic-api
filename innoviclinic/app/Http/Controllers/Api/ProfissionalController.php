<?php

namespace App\Http\Controllers\Api;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProfissionalRequest;
use App\Http\Requests\Api\UpdateProfissionalRequest;

class ProfissionalController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;
        $this->middleware('check-empresa-profissional-id')->only(['index', 'show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = Pessoa::query();
        $query->with('empresa_profissional')->where('tipo_usuario', 'Profissional');

        $query->whereHas('empresa_profissional', function ($subquery) use ($user) {
            $subquery->where('empresa_id', $user->empresa_profissional->empresa_id);
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
        $objeto = Pessoa::with([
            'empresa_profissional',
            'profissional',
            'profissional.especialidades' => function ($query) {
                // $query->select([
                //     DB::raw('profissional_especialidades.id'),
                //     DB::raw('profissional_especialidades.especialidade_id'),
                //     DB::raw('especialidades.id as value'),
                //     DB::raw('especialidades.nome as name')
                // ])->join('especialidades', 'profissional_especialidades.especialidade_id', '=', 'especialidades.id');

                $query->select('profissional_especialidades.*', 'especialidades.id as value', 'especialidades.nome as label');
                $query->join('especialidades', 'profissional_especialidades.especialidade_id', '=', 'especialidades.id');

                //$query->select(['id', 'profissional_id', 'especialidade_id']);
                //$query->with('especialidade:id,profissao_id,nome');
            }
        ])->find($id);
        if (!$objeto) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($objeto);
    }


    public function store(StoreProfissionalRequest $request)
    {
        $input = $request->validated();
        $input['tipo_usuario'] = 'Profissional';

        return DB::transaction(function () use ($input, $request) {
            $user = $this->customAuth->getUser();

            // Criação do profissional
            $pessoa = Pessoa::create($input);

            // Associa o profissional à empresa
            $pessoa->empresa_profissional()->create([
                'empresa_id' => $user->empresa_profissional->empresa_id,
                'profissional_id' => $user->empresa_profissional->profissional_id
            ]);

            // Dados adicionais do profissional
            if ($request->input('profissional')) {
                $profissionalData = $request->input('profissional');
                $pessoa->profissional()->create($profissionalData);

                // Armazena as especialidades
                $pessoa->profissional_especialidades()->attach($profissionalData['especialidades'], ['usuario_id' => $user->id, 'created_at' => now()]);
            }

            return response()->json($pessoa);
        });
    }

    public function update(UpdateProfissionalRequest $request, $id)
    {
        $input = $request->validated();
        $input['tipo_usuario'] = 'Profissional';

        return DB::transaction(function () use ($request, $input, $id) {
            $user = $this->customAuth->getUser();

            // Atualização do profissional
            $pessoa = Pessoa::findOrFail($id);
            $pessoa->update($input);

            // Associação do profissional à empresa (se necessário)
            $empresaProfissional = $pessoa->empresa_profissional;
            if (!$empresaProfissional) {
                $pessoa->empresa_profissional()->create([
                    'empresa_id' => $user->empresa_profissional->empresa_id,
                    'profissional_id' => $user->empresa_profissional->profissional_id,
                ]);
            }

            // Dados adicionais do profissional
            if ($request->input('profissional')) {
                $profissionalData = $request->input('profissional');

                // Atualiza ou cria o registro Profissional
                $profissional = $pessoa->profissional;
                if (!$profissional) {
                    $profissional = $pessoa->profissional()->create($profissionalData);
                } else {
                    $profissional->update($profissionalData);
                }

                // Sincroniza as especialidades
                $pessoa->profissional_especialidades()->syncWithPivotValues($profissionalData['especialidades'], ['usuario_id' => $user->id, 'created_at' => now()]);
            }

            return response()->json($pessoa);
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

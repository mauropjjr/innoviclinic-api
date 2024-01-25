<?php

namespace App\Http\Controllers\Api;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePacienteRequest;
use App\Http\Requests\Api\UpdatePacienteRequest;

class PacienteController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;
        $this->middleware('check-prontuario-paciente-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
{
    $user = $this->customAuth->getUser();
    $query = Pessoa::query();
    $query->with(['paciente', 'prontuario' => function ($query) use ($user) {
        $query->where('profissional_id', $user->empresa_profissional->profissional_id)
            ->where('empresa_id', $user->empresa_profissional->empresa_id);
    }])->where('tipo_usuario', 'Paciente');

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
        $user = $this->customAuth->getUser();
        $objeto = Pessoa::with([
            'paciente',
            'prontuario' => function ($query) use ($user) {
                $query->where('profissional_id', $user->empresa_profissional->profissional_id)
                    ->where('empresa_id', $user->empresa_profissional->empresa_id);
            }
        ])->find($id);

        if (!$objeto) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($objeto);
    }

    public function store(StorePacienteRequest $request)
    {
        $input = $request->validated();
        $input['tipo_usuario'] = 'Paciente';

        return DB::transaction(function () use ($input, $request) {
            // Criação do paciente
            $pessoa = Pessoa::create($input);

            $paciente = $request->input('paciente', []);
            $pessoa->paciente()->create($paciente);

            $user = $this->customAuth->getUser();
            $prontuario = $request->input('prontuario');
            if ($user->tipo_usuario != 'Paciente') {
                $prontuario['empresa_id'] = $user->empresa_profissional->empresa_id;
                $prontuario['profissional_id'] = $user->empresa_profissional->profissional_id;
                $pessoa->prontuarios()->create($prontuario);
            }

            return response()->json($pessoa);
        });
    }

    public function update(UpdatePacienteRequest $request, string $id)
    {
        $input = $request->validated();
        $input['tipo_usuario'] = 'Paciente';

        return DB::transaction(function () use ($request, $input, $id) {
            // Atualização da pessoa
            $pessoa = Pessoa::findOrFail($id);
            $pessoa->update($input);

            // Atualiza ou cria o registro do paciente
            $pacienteData = $request->input('paciente', []);
            $pessoa->paciente()->updateOrCreate([], $pacienteData);

            // Criação ou atualização do prontuário se o usuário não for do tipo 'Paciente'
            $user = $this->customAuth->getUser();
            if ($user->tipo_usuario !== 'Paciente') {
                $prontuarioData = $request->input('prontuario', []);
                $prontuarioData['empresa_id'] = $user->empresa_profissional->empresa_id;
                $prontuarioData['profissional_id'] = $user->empresa_profissional->profissional_id;

                // Verifica se o relacionamento já existe
                $prontuario = $pessoa->prontuarios()
                    ->where('paciente_id', $id)
                    ->where('empresa_id', $user->empresa_profissional->empresa_id)
                    ->where('profissional_id', $user->empresa_profissional->profissional_id)
                    ->first();

                if ($prontuario) {
                    $prontuario->update($prontuarioData);
                } else {
                    // Cria o prontuário se não existir
                    $pessoa->prontuarios()->create($prontuarioData);
                }
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

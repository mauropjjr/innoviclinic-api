<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Sala;
use App\Models\Pessoa;
use App\Models\Empresa;

use App\Models\Convenio;
use App\Models\AgendaTipo;
use App\Models\Profissional;
use Illuminate\Http\Request;
use App\Models\ProcedimentoTipo;
use Illuminate\Support\Facades\DB;
use App\Models\EmpresaConfiguracao;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\AuthRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\RegisterProfissionalRequest;

class AuthController extends Controller
{

    /**
     * @unauthenticated
     */
    public function auth(AuthRequest $request)
    {
        //$user = Pessoa::where('email', $request->email)->first();
        $query = Pessoa::query();
        $query->where('email', $request->email);
        $query->with('empresa_profissional');
        $query->with('profissional_secretaria');
        $user = $query->first();

        if (!$user || !Hash::check($request->senha, $user->senha)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas']
            ]);
        }

        if($user->tipo_usuario == 'Profissional'){
            $user->profissional = Profissional::select(['tratamento', 'nome_conselho', 'numero_conselho'])->where('pessoa_id', $user->id)->first();
        }

        // Sobrescreve a relação empresa_profissional se profissional_secretaria existir
        if ($user && $user->profissional_secretaria) {
            $user->setRelation('empresa_profissional', $user->profissional_secretaria);
        }
        unset($user->profissional_secretaria);


        // Logout others devices
        // if ($request->has('logout_others_devices'))
        //$user->tokens()->delete();

        $user->token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $request->pessoa()->tokens()->delete();

        return response()->json([
            'message' => 'success',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->pessoa();

        return response()->json([
            'me' => $user,
        ]);
    }

    public function register(RegisterProfissionalRequest $request)
    {
        $input = $request->validated();
        $input['tipo_usuario'] = 'Profissional';

        return DB::transaction(function () use ($input, $request) {
            // Criação do profissional
            $pessoa = Pessoa::create($input);

            // Dados adicionais do profissional
            if ($request->input('profissional')) {
                $profissionalData = $request->input('profissional');
                $profissionalData['usuario_id'] = $pessoa->id;
                $pessoa->profissional()->create($profissionalData);

                // Armazena as especialidades
                $pessoa->profissional_especialidades()->attach($profissionalData['especialidades'], ['usuario_id' => $pessoa->id, 'created_at' => now()]);
            }

            // Criação da empresa
            $empresa = Empresa::create([
                'tipo' => 'PF',
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->celular,
                'cpf_cnpj' => $request->cpf,
                'usuario_id' => $pessoa->id
            ]);

            // Associa o profissional à empresa
            $pessoa->empresa_profissional()->create([
                'empresa_id' => $empresa->id,
                'profissional_id' => $pessoa->id,
                'usuario_id' => $pessoa->id
            ]);

            event(new Registered($pessoa));
            Auth::login($pessoa);

            // Criação da Configuração Default da Empresa
            EmpresaConfiguracao::create([
                //'empresa_id' => $empresa->id,
                'hora_ini_agenda' => '08:00',
                'hora_fim_agenda' => '17:00',
                'duracao_atendimento' => '40',
                'visualizacao_agenda' => 'timeGridWeek'
            ]);

            // Criação da sala
            Sala::create([
                //'empresa_id' => $empresa->id,
                'nome' => 'Sala 1'
            ]);

            // Criação de convênio
            Convenio::create([
                //'empresa_id' => $empresa->id,
                'nome' => 'Particular',
                'tipo' => 'Particular'
            ]);

            // Criação de tipos de agenda
            $tipos = [
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id, 'cor' => '#0066FF', 'nome' => 'Consulta', 'sem_horario' => 0, 'sem_procedimento' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id, 'cor' => '#FFFF00', 'nome' => 'Retorno', 'sem_horario' => 0, 'sem_procedimento' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id, 'cor' => '#00FF00', 'nome' => 'Exame', 'sem_horario' => 0, 'sem_procedimento' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id, 'cor' => '#FF00FF', 'nome' => 'Encaixe', 'sem_horario' => 0, 'sem_procedimento' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id, 'cor' => '#800080', 'nome' => 'Procedimento', 'sem_horario' => 0, 'sem_procedimento' => 1, 'created_at' => now(), 'updated_at' => now()]
            ];
            AgendaTipo::insert($tipos);

            // Criação de tipos de procedimentos
            $tipos = [
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id,  'nome' => 'Cirurgia', 'created_at' => now(), 'updated_at' => now()],
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id,  'nome' => 'Emergência', 'created_at' => now(), 'updated_at' => now()],
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id,  'nome' => 'Exame Clínico', 'created_at' => now(), 'updated_at' => now()],
                ['empresa_id' => $empresa->id, 'usuario_id' => $pessoa->id,  'nome' => 'Prevenção', 'created_at' => now(), 'updated_at' => now()],
            ];
            ProcedimentoTipo::insert($tipos);

            return response()->json($pessoa);
        });
    }
}

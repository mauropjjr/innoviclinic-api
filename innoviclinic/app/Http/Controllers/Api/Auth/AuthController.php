<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Sala;
use App\Models\Pessoa;
use App\Models\Empresa;

use Illuminate\Http\Request;
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

        // Sobrescreve a relação empresa_profissional se profissional_secretaria existir
        if ($user && $user->profissional_secretaria) {
            $user->setRelation('empresa_profissional', $user->profissional_secretaria);
        }
        unset($user->profissional_secretaria);


        if (!$user || !Hash::check($request->senha, $user->senha)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas']
            ]);
        }

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

            event(new Registered($pessoa));
            Auth::login($pessoa);

            // Dados adicionais do profissional
            if ($request->input('profissional')) {
                $profissionalData = $request->input('profissional');
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
            ]);

            // Associa o profissional à empresa
            $pessoa->empresa_profissional()->create([
                'empresa_id' => $empresa->id,
                'profissional_id' => $pessoa->id
            ]);

            // Criação da Configuração Default da Empresa
            EmpresaConfiguracao::create([
                'empresa_id' => $empresa->id,
                'hora_ini_agenda' => '08:00',
                'hora_fim_agenda' => '17:00',
                'duracao_atendimento' => '40',
                'visualizacao_agenda' => 'timeGridWeek'
            ]);

            // Criação da sala
            Sala::create([
                'empresa_id' => $empresa->id,
                'nome' => "Sala 1"
            ]);

            return response()->json($pessoa);
        });
    }
}

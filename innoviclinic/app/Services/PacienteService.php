<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Auth;

class PacienteService
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;
    }

    public function create(array $data)
    {
        $data['tipo_usuario'] = 'Paciente';
        try {
            $paciente = DB::transaction(function () use ($data) {
                // Criação do paciente
                $pessoa = Pessoa::create($data);
                $pessoa->paciente()->create($data);
                $user = $this->customAuth->getUser();
                $prontuario = $data["prontuario"] ?? [];
                if ($user->tipo_usuario != 'Paciente') {
                    $prontuario['empresa_id'] = $user->empresa_profissional->empresa_id;
                    $prontuario['profissional_id'] = $user->empresa_profissional->profissional_id;
                    $pessoa->prontuarios()->create($prontuario);
                }
                return $pessoa->paciente;
            });
            return $paciente;
        } catch (\Exception $e) {
            // DB::rollback();
            throw $e;
        }
    }
}

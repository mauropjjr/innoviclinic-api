<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Pessoa;

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
                $pessoaPaciente = $data["paciente"] ?? [];
                $pessoa->paciente()->create($pessoaPaciente);
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

    public function getAgendas(array $data)
    {
        // return $data;
        $objeto = Pessoa::query();
        $objeto->where("tipo_usuario", "Paciente");
        /**
         * verificando se é pra pegar por telefone ou email
        */
        $tel = $data["telefone"] ?? false;
        $email = $data["email"] ?? false;
        if ($tel) {
            $objeto->where("celular", $tel);
        }
        if ($email) {
            $objeto->where("email", $email);
        }
        // echo $objeto->toSql();die();
        // return $objeto->get();
        // return $data["agendasStatus"];
        $objeto
        ->with([
            'paciente' => function ($query) use ($data) {
                $query->with([
                    "agendas" => function($query) use ($data) {
                        $query
                        ->with("agenda_status")
                        ->orderBy("agenda_status_id", "asc");
                        $agenda_status = $data["agendasStatus"] ?? [];
                        if (count($agenda_status) > 0) {
                            $query->whereIn("agenda_status_id", $data["agendasStatus"]);
                        }
                    }
                ]);
            }
        ]);
        // echo $objeto->toSql();die();
        return $objeto->get()->toArray();

    }
}

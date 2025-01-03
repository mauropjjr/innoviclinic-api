<?php

namespace App\Http\Public;

use App\Models\Agenda;
use App\Models\Empresa;
use App\Services\AgendaService;
use App\Services\CustomAuthService;
use App\Services\PacienteService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isEmpty;

class PacientePublic
{
    protected $customAuthService;


    public function __construct(CustomAuthService $customAuthService)
    {
        $this->customAuthService = $customAuthService;
    }


    public function getAgendas(Request $request)
    {
        $request->validate([
            'empresa_id' => ['required', Rule::exists('empresas', 'id')],
            'email' => [
                'nullable',
                'email',
                'required_without:telefone',
                function ($attribute, $value, $fail) use ($request) {
                    // Verifica se tanto email quanto telefone estão preenchidos
                    if (!empty($value) && !empty($request->input('telefone'))) {
                        $fail('Você não pode preencher tanto o email quanto o telefone.');
                    }
                }
            ],
            'telefone' => [
                'nullable',
                'string',
                'required_without:email',
                // 'regex:/^67\d{9}$/',  // Valida o formato: 67 seguido de 9 dígitos
                function ($attribute, $value, $fail) use ($request) {
                    // Verifica se tanto email quanto telefone estão preenchidos
                    if (!empty($value) && !empty($request->input('email'))) {
                        $fail('Você não pode preencher tanto o email quanto o telefone.');
                    }
                }
            ],
            'agendasStatus' => ['nullable']
        ]);

        // return $request->agendasStatus;
        $response = Agenda::query();
        $data = $request->all();

        if (isset($data["telefone"])) {
            $response->where("celular", $data["telefone"]);
        }
        if (isset($data["email"])) {
            $response->where("email", $data["email"]);
        }

        $response->where("empresa_id", $data["empresa_id"])->where("agenda_status_id", "!=", 3);


        if ($response && $response->count() == 0) {
            return response()->json([
                "message" => "Paciente não encontrado"
            ], 404);
        }
        return response()->json($response->get(), 200);
    }
}

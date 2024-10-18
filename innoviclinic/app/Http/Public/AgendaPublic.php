<?php 

namespace App\Http\Public;

use App\Models\Pessoa;
use App\Services\AgendaService;
use App\Services\CustomAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AgendaPublic
{
    protected $customAuthService;
    public function __construct(CustomAuthService $customAuthService) {
        $this->customAuthService = $customAuthService;
    }
    public function store(Request $request)
    {
        $input = $request->validate([
            'empresa_id' => 'required|integer|exists:empresas,id',
            'agenda_tipo_id' => [
                'required',
                'integer',
                Rule::exists('agenda_tipos', 'id')->where('empresa_id', $request->input("empresa_id")),
            ],
            'agenda_status_id' => 'integer|exists:agenda_status,id',
            'profissional_id' => 'required|integer|exists:profissionais,pessoa_id',
            'sala_id' => [
                'nullable',
                'integer',
                Rule::exists('salas', 'id')->where('empresa_id', $request->input("empresa_id")),
            ],
            'paciente_id' => [
                'nullable',
                'integer',
                function($attribute, $value, $fail) {
                    if (!is_null($value)) {
                        Rule::exists('pessoas', 'id')->where('tipo_usuario', 'Paciente');
                    }
                },
            ],
            'convenio_id' => [
                'nullable',
                'integer',
                Rule::exists('convenios', 'id')->where('empresa_id', $request->input("empresa_id")),
            ],
            'nome' => 'required|string|max:100',
            'celular' => 'nullable|string|max:20',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|string|max:100',
            'primeiro_atend' => 'required|boolean',
            'enviar_msg' => 'required|boolean',
            'telemedicina' => 'required|boolean',
            'data' => 'required|date',
            'hora_ini' => ['required', 'string', 'max:5'],
            'hora_fim' => ['required', 'string', 'max:5'],
            'valor' => 'required|numeric',

            // Validação dos procedimentos se preenchido
            'procedimentos' => 'nullable|array',
            'procedimentos.*.procedimento_id' => [
                'required_with:procedimentos',
                'integer',
                Rule::exists('procedimentos', 'id')->where('empresa_id', $request->input("empresa_id")),
            ],
            'procedimentos.*.qtde' => 'required_with:procedimentos|integer|min:1',
            'procedimentos.*.valor' => 'required_with:procedimentos|numeric|min:0'
        ]);
        Auth::login(Pessoa::find($request->profissional_id));
        $agenda = (new AgendaService($this->customAuthService))->create($input);
        return response()->json($agenda);
    }
}
<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Services\CustomAuthService;
use Illuminate\Foundation\Http\FormRequest;

class StoreAgendaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(CustomAuthService $customAuth): array
    {
        $empresaIdDoUsuarioLogado = $customAuth->getUser()->empresa_profissional->empresa_id;

        return [
            'empresa_id' => 'integer|exists:empresas,id',
            'agenda_tipo_id' => [
                'required',
                'integer',
                Rule::exists('agenda_tipos', 'id')->where('empresa_id', $empresaIdDoUsuarioLogado),
            ],
            'agenda_status_id' => 'integer|exists:agenda_status,id',
            'profissional_id' => 'required|integer|exists:profissionais,pessoa_id',
            'sala_id' => [
                'nullable',
                'integer',
                Rule::exists('salas', 'id')->where('empresa_id', $empresaIdDoUsuarioLogado),
            ],
            'paciente_id' => [
                'required',
                Rule::exists('pessoas', 'id')->where('tipo_usuario', 'Paciente'),
            ],
            'convenio_id' => [
                'nullable',
                'integer',
                Rule::exists('convenios', 'id')->where('empresa_id', $empresaIdDoUsuarioLogado),
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
                Rule::exists('procedimentos', 'id')->where('empresa_id', $empresaIdDoUsuarioLogado),
            ],
            'procedimentos.*.qtde' => 'required_with:procedimentos|integer|min:1',
            'procedimentos.*.valor' => 'required_with:procedimentos|numeric|min:0',
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages()
    {
        return [
            'procedimentos.*.procedimento_id.required_with' => 'O campo procedimento_id é obrigatório se o campo procedimentos estiver presente.',
            'procedimentos.*.qtde.required_with' => 'A quantidade é obrigatória para cada procedimento informado.',
            'procedimentos.*.valor.required_with' => 'O valor é obrigatório para cada procedimento informado.',
        ];
    }
}

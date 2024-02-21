<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
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
    public function rules(): array
    {
        return [
            'empresa_id' => 'integer|exists:empresas,id',
            'agenda_tipo_id' => 'integer|exists:agenda_tipos,id',
            'agenda_status_id' => 'integer|exists:agenda_status,id',
            'profissional_id' => 'required|integer|exists:profissionais,pessoa_id',
            'sala_id' => 'required|integer|exists:salas,id',
            'paciente_id' => [
                'required',
                Rule::exists('pessoas', 'id')->where(function ($query) {
                    $query->where('tipo_usuario', 'Paciente');
                }),
            ],
            'convenio_id' => 'required|integer|exists:convenios,id',
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
            'valor' => 'required',
        ];
    }
}

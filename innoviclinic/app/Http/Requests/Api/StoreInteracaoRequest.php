<?php

namespace App\Http\Requests\Api;

use App\Models\Interacao;
use Illuminate\Foundation\Http\FormRequest;

class StoreInteracaoRequest extends FormRequest
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
            'prontuario_id' => 'required|integer|exists:prontuarios,id',
            'agenda_id' => 'nullable|integer|exists:agendas,id',
            //'data' => 'required|date',
            //'hora_ini' => ['required', 'string', 'max:5'],
            'hora_fim' => ['nullable', 'string', 'max:5'],
            'tempo_atendimento' => ['nullable', 'string', 'max:5'],
            'finalizado' => 'boolean',
            'teleatendimento' => 'boolean',
            // Regra personalizada para verificar se já existe uma interação em aberto
            'prontuario_id' => [
                function ($attribute, $value, $fail) {
                    $prontuarioId = $this->input('prontuario_id');

                    // Verificar se existe uma interação em aberto para o prontuário/agenda fornecido
                    $exists = Interacao::where('prontuario_id', $prontuarioId)
                        ->where('finalizado', 0) // Interacao em aberto
                        ->exists();

                    if ($exists) {
                        $fail('Já existe uma interação de atendimento em aberto para este prontuário.');
                    }
                }
            ]
        ];
    }
}

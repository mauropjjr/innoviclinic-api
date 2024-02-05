<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfissionalAgendaRequest extends FormRequest
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
            'profissional_id' => 'integer|exists:profissionais,id',
            'dia' => 'required|integer',
            'hora_ini' => ['required', 'string', 'max:5'],
            'hora_fim' => ['required', 'string', 'max:5'],
        ];
    }
}

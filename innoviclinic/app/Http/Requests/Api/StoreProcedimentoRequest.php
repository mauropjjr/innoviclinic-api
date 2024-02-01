<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcedimentoRequest extends FormRequest
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
            'procedimento_tipo_id' => 'required|integer|exists:procedimento_tipos,id',
            'nome' => ['required', 'string', 'max:255'],
            'cor' => ['required', 'string', 'max:100'],
            'duracao_min' => 'required|integer',
            'valor' => 'required|decimal:2',
            'ativo'  => 'boolean'
        ];
    }
}

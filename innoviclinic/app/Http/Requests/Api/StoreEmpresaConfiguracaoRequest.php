<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaConfiguracaoRequest extends FormRequest
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
            'hora_ini_agenda' => ['nullable', 'string', 'max:5'],
            'hora_fim_agenda' => ['nullable', 'string', 'max:5'],
            'duracao_atendimento' => ['nullable', 'string', 'max:5'],
            'visualizacao_agenda' => ['nullable', 'string', 'max:100'],
            'ativo'  => 'boolean|bin:true,false'
        ];
    }
}

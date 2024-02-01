<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProfissionalSecretariaRequest extends FormRequest
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
            'profissional_id' => 'integer|exists:profissionais,pessoa_id',
            'secretaria_id' => [
                'required',
                Rule::exists('pessoas', 'id')->where(function ($query) {
                    $query->where('tipo_usuario', 'Secretaria');
                }),
            ],
            'ativo'  => 'boolean'
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use App\Rules\Cpf;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePacienteRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('pessoas', 'email')->ignore(request()->route('id')),
            ],
            'senha' => 'max:255',
            'celular' => 'required|max:20',
            'telefone' => 'nullable|max:20',
            'cpf' => [
                'nullable',
                new Cpf,
                Rule::unique('pessoas', 'cpf')->ignore(request()->route('id')),
            ],
            'cep' => 'max:9',
            'uf' => 'max:2',
            'observacoes' => 'max:1000',
            'ativo' => 'boolean',
        ];
    }
}

<?php

namespace App\Http\Requests\Auth;

use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;

class RegisterProfissionalRequest extends FormRequest
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
            'email' => 'required|email|max:100|unique:pessoas,email',
            'senha' => 'required|max:255',
            'celular' => 'required|max:20',
            'telefone' => 'nullable|max:20',
            'cpf' => ['nullable', new Cpf, 'unique:pessoas,cpf'],
            'cep' => 'max:9',
            'uf' => 'max:2',
            'observacoes' => 'max:1000',
            'ativo' => 'boolean',
            'profissional.profissao_id' => 'required|numeric',
            'profissional.nome_conselho' => 'required|max:20|string',
            'profissional.numero_conselho' => 'required|max:20|string',
            'profissional.especialidades' => 'required|array|min:1',
            'profissional.especialidades.*' => 'required|numeric|exists:especialidades,id',
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use App\Rules\CpfCnpj;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
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
            'tipo' => ['required', 'string','max:2'],
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:100'],
            'telefone' => ['required', 'max:20'],
            'cpf_cnpj' => ['nullable', new CpfCnpj, 'unique:empresas,cpf_cnpj'],
            'profissional_id' => 'required|exists:profissionais,pessoa_id',
            'razao_social' => ['max:255'],
            'cep' => ['max:9'],
            'uf' => ['max:2'],
            'ativo' => 'boolean',
        ];
    }
}

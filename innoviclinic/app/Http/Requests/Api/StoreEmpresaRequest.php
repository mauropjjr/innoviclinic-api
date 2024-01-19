<?php

namespace App\Http\Requests\Api;

use App\Helpers\Utils;
use App\Rules\CpfCnpj;
use Illuminate\Validation\Rule;
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
            'ativo' => 'boolean|in:true,false',
            'cpf_cnpj' => ['string', new CpfCnpj, 'unique:empresas,cpf_cnpj']
        ];
    }
}

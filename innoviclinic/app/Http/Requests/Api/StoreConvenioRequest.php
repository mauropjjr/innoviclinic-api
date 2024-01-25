<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreConvenioRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'string', 'in:Convênio,Particular'],
            'numero_registro' => ['nullable', 'string', 'max:255'],
            'ativo'  => 'boolean|bin:true,false'
        ];
    }
}

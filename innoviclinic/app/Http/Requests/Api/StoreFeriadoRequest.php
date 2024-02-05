<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeriadoRequest extends FormRequest
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
            'data' => 'required|date',
            'nome' => ['required', 'string', 'max:100'],
            'descricao' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'string', 'in:Feriado Nacional,Feriado Estadual,Feriado Municipal,Dia Convencional'],
        ];
    }
}

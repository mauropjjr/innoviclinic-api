<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Services\CustomAuthService;
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
    public function rules(CustomAuthService $customAuth): array
    {
        $empresaIdDoUsuarioLogado = $customAuth->getUser()->empresa_profissional->empresa_id;

        return [
            'empresa_id' => 'integer|exists:empresas,id',
            'procedimento_tipo_id' => [
                'required',
                'integer',
                Rule::exists('procedimento_tipos', 'id')->where('empresa_id', $empresaIdDoUsuarioLogado),
            ],
            'nome' => ['required', 'string', 'max:255'],
            'duracao_min' => 'required|integer',
            'valor' => 'required',
            'preparacao' => ['nullable', 'string', 'max:1000'],
            'observacao' => ['nullable', 'string', 'max:1000'],
            'ativo'  => 'boolean',


            // Validação dos convênios se preenchido
            'convenios' => 'nullable|array',
            'convenios.*.convenio_id' => [
                'required_with:convenios',
                'integer',
                Rule::exists('convenios', 'id')->where('empresa_id', $empresaIdDoUsuarioLogado),
            ],
            'convenios.*.valor' => 'required_with:convenios|numeric|min:0',

        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages()
    {
        return [
            'convenios.*.convenio_id.required_with' => 'O campo convenio_id é obrigatório se o campo convenios estiver presente.',
            'convenios.*.valor.required_with' => 'O valor é obrigatório para cada procedimento informado.',
        ];
    }
}

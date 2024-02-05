<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Services\CustomAuthService;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventoRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255'],
            'data_ini' => 'required|date',
            'data_fim' => 'required|date',
            'descricao' => ['nullable','string', 'max:255'],
            'hora_ini' => ['required', 'string', 'max:5'],
            'hora_fim' => ['required', 'string', 'max:5'],
            'dias_semana' => ['required', 'string', 'max:20'],
            'cor' => ['required', 'string', 'max:50'],
            'profissionais' => 'required|array|min:1',
            'profissionais.*' => [
                'required',
                'numeric',
                Rule::exists('empresa_profissionais', 'profissional_id')->where(function ($query) use ($empresaIdDoUsuarioLogado) {
                    $query->where('empresa_id', $empresaIdDoUsuarioLogado);
                }),
            ],
        ];
    }
}

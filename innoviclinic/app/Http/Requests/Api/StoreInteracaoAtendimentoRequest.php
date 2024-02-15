<?php

namespace App\Http\Requests\Api;

use App\Models\Interacao;
use Illuminate\Foundation\Http\FormRequest;

class StoreInteracaoAtendimentoRequest extends FormRequest
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
            'interacao_id' => 'required|integer|exists:interacoes,id',
            'secao_id' => 'required|integer|exists:secoes,id',
            'descricao' => ['required', 'string'],
            // Regra personalizada para verificar a interação está em aberto
            'interacao_id' => [
                function ($attribute, $value, $fail) {
                    $interacaoId = $this->input('interacao_id');

                    // Verificar se existe uma interação em aberto
                    $exists = Interacao::where('id', $interacaoId)
                        ->where('finalizado', 0) // Interacao em aberto
                        ->exists();

                    if (!$exists) {
                        $fail('Esse atendimento já foi finalizado, por favor inicie um novo atendimento.');
                    }
                }
            ]
        ];
    }
}

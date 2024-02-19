<?php

namespace App\Services;

use App\Models\Prontuario;

class ProntuarioService
{
    /**
     * Create a new prontuario if it doesn't already exist.
     *
     * @param array $data
     * @return Prontuario
     */
    public function createIfNotExists(array $data): Prontuario
    {
        // Verifica se já existe um prontuário com as mesmas chaves únicas
        $existingProntuario = Prontuario::where([
            'paciente_id' => $data['paciente_id'],
            'empresa_id' => $data['empresa_id'],
            'profissional_id' => $data['profissional_id'],
        ])->first();

        if ($existingProntuario) {
            return $existingProntuario;
        }

        // Cria o prontuário se não existir
        return Prontuario::create($data);
    }
}

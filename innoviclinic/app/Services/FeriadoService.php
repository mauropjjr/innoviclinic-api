<?php

namespace App\Services;

use App\Models\Feriado;

class FeriadoService
{
    public function verificarSeDiaFeriado(array $data)
    {
        // Verificar se é feriado na data desejada para a empresa específica ou para todos (feriado nacional)
        $feriado = Feriado::where(function ($query) use ($data) {
            $query->where('data', $data['data'])
                ->where('empresa_id', $data['empresa_id']);
        })
            ->orWhere(function ($query) use ($data) {
                $query->where('data', $data['data'])
                    ->whereNull('empresa_id'); // Feriado nacional
            })
            ->exists();
        return ['success' => $feriado ? false : true, 'message' => $feriado ? 'Horário não disponível devido a um feriado.' : null];
    }
}

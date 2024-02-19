<?php

namespace App\Services;

use App\Models\Evento;

class EventoService
{
    public function verificarSeNaoHaEventosNesteDiaHorario(array $data)
    {
        // Verificar se há eventos agendados para a mesma data e horário
        $evento = Evento::where('empresa_id', $data['empresa_id'])
            ->where('data_ini', '<=', $data['data'])
            ->where('data_fim', '>=', $data['data'])
            ->where('hora_ini', '<=', $data['hora_ini'])
            ->where('hora_fim', '>=', $data['hora_fim'])
            ->whereHas('evento_profissionais', function ($query) use ($data) {
                $query->where('profissional_id', $data['profissional_id']);
            })
            ->exists();
        return ['success' => $evento ? false : true, 'message' => $evento ? 'Horário não disponível devido a um evento.' : null];
    }
}

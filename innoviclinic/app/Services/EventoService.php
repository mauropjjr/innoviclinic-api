<?php

namespace App\Services;

use App\Models\Evento;
use Illuminate\Support\Facades\DB;

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

    public function listAgenda(array $filtros)
    {
        // Lógica para buscar os eventos da agenda com base nos filtros
        $eventos = Evento::select(
            'eventos.id',
            DB::raw("'evento' as tipo"),
            'eventos.empresa_id',
            DB::raw('NULL as paciente_id'),
            DB::raw('NULL as sala_id'),
            DB::raw('NULL as prontuario_id'),
            DB::raw('NULL as agenda_tipo'),
            DB::raw('NULL as cor'),
            'eventos.nome',
            'eventos.data_ini as data',
            'eventos.hora_ini',
            'eventos.hora_fim',
            'eventos.data_ini as data_ini',
            'eventos.data_fim as data_fim',
            'eventos.dias_semana',
            DB::raw('NULL as sem_horario'),
            DB::raw('NULL as primeiro_atend')
        )
            ->join('evento_profissionais', 'eventos.id', '=', 'evento_profissionais.evento_id')
            ->where('eventos.empresa_id', $filtros['empresa_id'])
            ->where('evento_profissionais.profissional_id', $filtros['profissional_id'])
            ->where(function ($query) use ($filtros) {
                $query->whereBetween('eventos.data_ini', [$filtros['data_inicio'], $filtros['data_fim']])
                    ->orWhereBetween('eventos.data_fim', [$filtros['data_inicio'], $filtros['data_fim']]);
            })
            ->distinct();

        return $eventos->get();
    }
}

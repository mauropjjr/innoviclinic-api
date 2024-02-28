<?php

namespace App\Services;

use App\Models\Feriado;
use Illuminate\Support\Facades\DB;

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

    public function listAgenda(array $filtros)
    {
        // Lógica para buscar os feriados da agenda com base nos filtros
        $feriados = Feriado::select(
            'id',
            DB::raw("'feriado' as tipo"),
            'empresa_id',
            DB::raw('NULL as paciente_id'),
            DB::raw('NULL as sala_id'),
            DB::raw('NULL as prontuario_id'),
            DB::raw('NULL as agenda_tipo'),
            DB::raw("'#FF0000' as cor"),
            'nome',
            'data',
            DB::raw('NULL as hora_ini'),
            DB::raw('NULL as hora_fim'),
            'data as data_ini',
            'data as data_fim',
            DB::raw('NULL as dias_semana'),
            DB::raw('NULL as sem_horario'),
            DB::raw('NULL as primeiro_atend')
        )
            ->where(function ($query) use ($filtros) {
                $query->whereBetween('data', [$filtros['data_inicio'], $filtros['data_fim']])
                    ->where('empresa_id', $filtros['empresa_id']);
            })
            ->orWhere(function ($query) use ($filtros) {
                $query->whereBetween('data', [$filtros['data_inicio'], $filtros['data_fim']])
                    ->whereNull('empresa_id'); // Feriado nacional;
            });

        return $feriados->get();
    }
}

<?php

namespace App\Services;

use App\Models\ProfissionalAgenda;

class ProfissionalAgendaService
{
    public function verificarSeEstaDisponivelNesteDiaHorario(array $data)
    {

        $profissionalAgenda = ProfissionalAgenda::where('empresa_id', $data['empresa_id'])
            ->where('profissional_id', $data['profissional_id'])
            ->exists();

        //Se é um profissional novo ou não definiu sua agenda por dias da semana, permitimos gerar para qualquer dia da semana
        if(!$profissionalAgenda){
            return ['success' => true, 'message' => null];
        }

        // Verificar se o profissional está disponível no dia da semana e horário desejados
        $profissionalAgenda = ProfissionalAgenda::where('empresa_id', $data['empresa_id'])
            ->where('profissional_id', $data['profissional_id'])
            ->where('dia', date('N', strtotime($data['data']))) // Dia da semana (1 para segunda, 2 para terça, etc.)
            ->where('hora_ini', '<=', $data['hora_ini'])
            ->where('hora_fim', '>=', $data['hora_fim'])
            ->exists();
        return ['success' => !$profissionalAgenda ? false : true, 'message' => !$profissionalAgenda ? 'Profissional não disponível neste dia e/ou horário.' : null];
    }
}

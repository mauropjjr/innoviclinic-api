<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait AutoSetControleInteracao
{
    protected static function bootAutoSetControleInteracao()
    {

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->usuario_id = $user->id;
            }
            $model->data = date('Y-m-d');
            $model->hora_ini = date('H:i');
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->usuario_id = $user->id;
            }

            $model->hora_fim = $model->finalizado == 1 ? date('H:i') : null;
            $model->tempo_atendimento = $model->finalizado == 1 ? self::getTempoAtendimento($model) : null;
        });
    }

    private static function getTempoAtendimento($model)
    {
        // Calcula o tempo de atendimento se a interação estiver sendo finalizada
        $horaIni = Carbon::createFromFormat('H:i', $model->hora_ini);
        $horaFim = Carbon::createFromFormat('H:i', $model->hora_fim);
        return $horaIni->diff($horaFim)->format('%H:%I');
    }
}

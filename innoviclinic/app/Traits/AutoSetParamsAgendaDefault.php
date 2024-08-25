<?php

namespace App\Traits;

use App\Models\Convenio;
use Illuminate\Support\Facades\Auth;
use App\Models\Sala;

trait AutoSetParamsAgendaDefault
{
    protected static function bootAutoSetParamsAgendaDefault()
    {
        static::creating(function ($model) {
            $user = Auth::user();

            // Verifica se o modelo ainda não tem sala_id e se a empresa está definida
            if (!$model->sala_id && $user->empresa_profissional->empresa_id) {

                $primeiraSala = Sala::where('empresa_id', $user->empresa_profissional->empresa_id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($primeiraSala) {
                    $model->sala_id = $primeiraSala->id;
                }
            }

            // Verifica se o modelo ainda não tem convenio_id e se a empresa está definida
            if (!$model->convenio_id && $user->empresa_profissional->empresa_id) {

                $primeiroConvenio = Convenio::where('empresa_id', $user->empresa_profissional->empresa_id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($primeiroConvenio) {
                    $model->convenio_id = $primeiroConvenio->id;
                }
            }
        });
    }
}

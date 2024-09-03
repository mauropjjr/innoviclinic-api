<?php

namespace App\Traits;

use App\Models\Convenio;
use App\Models\Sala;
use App\Services\CustomAuthService;
use Illuminate\Container\Container;

trait AutoSetParamsAgendaDefault
{
    protected $customAuthService;

    protected function getCustomAuth()
    {
        return Container::getInstance()->make(CustomAuthService::class);
    }

    protected static function bootAutoSetParamsAgendaDefault()
    {
        static::creating(function ($model) {
            $customAuth = (new self)->getCustomAuth();
            $user = $customAuth->getUser();

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

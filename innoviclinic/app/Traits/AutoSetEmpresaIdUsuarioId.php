<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AutoSetEmpresaIdUsuarioId
{
    protected static function bootAutoSetEmpresaIdUsuarioId()
    {

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->usuario_id  = $user->id;
                $model->empresa_id = 1;
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->usuario_id  = $user->id;
                unset($model->empresa_id);
            }
        });
    }
}

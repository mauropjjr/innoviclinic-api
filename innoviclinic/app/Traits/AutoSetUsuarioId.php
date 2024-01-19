<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AutoSetUsuarioId
{
    protected static function bootAutoSetUsuarioId()
    {

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->usuario_id = $user->id;
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->usuario_id = $user->id;
            }
        });
    }
}

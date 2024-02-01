<?php

namespace App\Traits;

use App\Services\CustomAuthService;
use Illuminate\Container\Container;

trait AutoSetEmpresaIdProfissionalIdUsuarioId
{
    protected $customAuthService;


    protected function getCustomAuth()
    {
        return Container::getInstance()->make(CustomAuthService::class);
    }

    protected static function bootAutoSetEmpresaIdProfissionalIdUsuarioId()
    {
        $customAuth = (new self)->getCustomAuth();

        static::creating(function ($model) use($customAuth) {
            $user = $customAuth->getUser();
            if ($user) {
                $model->usuario_id = $user->id;
                $model->empresa_id = $user->empresa_profissional->empresa_id;
                $model->profissional_id = $user->empresa_profissional->profissional_id;
            }
        });

        static::updating(function ($model) use($customAuth){
            $user = $customAuth->getUser();
            if ($user) {
                $model->usuario_id = $user->id;
                unset($model->empresa_id);
                unset($model->profissional_id);
            }
        });
    }
}

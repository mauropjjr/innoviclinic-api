<?php

namespace App\Services;

use App\Models\Pessoa;
use App\Models\Profissional;
use Illuminate\Support\Facades\Auth;

class CustomAuthService
{
    public function getUser()
    {
        $auth = Auth::user();
        $user = null;
        if (isset($auth->id) && $auth->id) {
            $query = Pessoa::query();
            $query->where('id', $auth->id);
            $query->with('empresa_profissional');
            $query->with('profissional_secretaria');
            $user = $query->first();

            if($user->tipo_usuario == 'Profissional'){
                $user->profissional = Profissional::select(['tratamento', 'nome_conselho', 'numero_conselho'])->where('pessoa_id', $user->id)->first();
            }

            // Sobrescreve a relação empresa_profissional se profissional_secretaria existir
            if ($user->profissional_secretaria) {
                $user->setRelation('empresa_profissional', $user->profissional_secretaria);
            }
            unset($user->profissional_secretaria);
        }

        return $user;
    }
}

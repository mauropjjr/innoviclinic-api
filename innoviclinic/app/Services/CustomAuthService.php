<?php

namespace App\Services;

use App\Models\Pessoa;
use Illuminate\Support\Facades\Auth;

class CustomAuthService
{
    public function getUser()
    {
        $user = Auth::user();
        $query = Pessoa::query();
        $query->where('id', $user->id)->with('empresa_profissional');

        return $query->first();
    }
}

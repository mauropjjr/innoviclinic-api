<?php 

namespace App\Http\Public;

use App\Models\Pessoa;
use App\Services\AgendaService;
use App\Services\CustomAuthService;
use App\Services\ProfissionalService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfissionalPublic
{
    protected $customAuthService;
    public function __construct(CustomAuthService $customAuthService) {
        $this->customAuthService = $customAuthService;
    }

    public function getAgendas(int $profissional_id, Request $request)
    {
        $request->validate([
            "dataHora" => "required|date"
        ]);
        
        $objeto = Pessoa::findOr($profissional_id, fn () => response()->json([
            "message" => __("User not found")
        ], Response::HTTP_NOT_FOUND));

        if (!$objeto instanceof Pessoa) {
            return $objeto;
        }

        Auth::login($objeto);
        return (new ProfissionalService($this->customAuthService))->getAgendas([
            "profissional_id" => $profissional_id,
            "dataHora" => $request->dataHora
        ]);
    }
}
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
            "dataHora" => "required|date",
            "profissional_id" => "required|exists:pessoas,id",
            "empresa_id" => "required|exists:empresas,id"
        ]);

        $objeto = Pessoa::findOr($profissional_id, fn () => response()->json([
            "message" => __("User not found")
        ], Response::HTTP_NOT_FOUND));



        if (!$objeto instanceof Pessoa) {
            return $objeto;
        }

        Auth::login(Pessoa::find($request->profissional_id));
        return (new ProfissionalService($this->customAuthService))->getAgendas($request->all());
    }
    public function getAgendasDisponiveis(int $profissional_id, Request $request)
    {
        // return $profissional_id;
        $request->validate([
            "dataHora" => "required|date",
            "empresa_id" => "required|exists:empresas,id"
        ]);

        $objeto = Pessoa::findOr($profissional_id, fn () => response()->json([
            "message" => __("User not found")
        ], Response::HTTP_NOT_FOUND));

        if (!$objeto instanceof Pessoa) {
            return $objeto;
        }

        $request->merge(["profissional_id" => $profissional_id]);

        Auth::login($objeto);
        return (new ProfissionalService($this->customAuthService))->getHorariosDisponiveis($request->all());
    }
}

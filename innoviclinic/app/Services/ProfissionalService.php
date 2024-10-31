<?php

namespace App\Services;

use App\Models\Profissional;
use Carbon\Carbon;

class ProfissionalService
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;
    }

    public function getAgendas(array $data)
    {
        return Profissional::with([
            "agendas" => function ($query) use ($data) {
                $query
                ->where([
                    ["data", ">=", $data["dataHora"]],
                    ["data", "<", (Carbon::parse($data["dataHora"]))->addDays(3)]
                ])
                ->select()
                ->orderBy("data", "asc")
                ->orderBy("hora_ini", "asc");
            }
        ])
        ->where([
            ["pessoa_id", "=", $data["profissional_id"]]
        ])
        ->get()
        ;
    }
}

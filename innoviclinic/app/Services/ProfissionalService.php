<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\EmpresaConfiguracao;
use App\Models\Profissional;
use App\Models\ProfissionalAgenda;
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
    // public function getHorariosDisponiveis(array $data)
    // {
    //         $empresaConfig = EmpresaConfiguracao::where("empresa_id", $data["empresa_id"])->first();
    //         $dayStart = $empresaConfig->hora_ini_agenda;
    //         $dayEnd = $empresaConfig->hora_fim_agenda;
            
    //         $date = Carbon::parse($data["dataHora"]);
    //         $endDate = $date->copy()->addDays(1);
    //         $occupiedAgendas = Agenda::where('profissional_id', $data["profissional_id"])
    //             ->where('data', '=', $data["dataHora"])
    //             // ->where("data", "<", $endDate)
    //             ->orderBy("data")
    //             ->orderBy("hora_ini")
    //             ->orderBy("hora_fim")
    //             ->get(['hora_ini', 'hora_fim', 'data'])
    //             ->toArray();

    //         // return $occupiedAgendas;
    //         $dayStarCarbon = Carbon::parse($dayStart);
    //         $firstTimeCarbon = Carbon::parse($occupiedAgendas[0]["hora_ini"]);
    //         $primeiroIntervalo = $dayStarCarbon->diffInMinutes($firstTimeCarbon);
    //         $disponiveis = [];
    //         if ($primeiroIntervalo >= 60) {
    //             $disponiveis[] = [
    //                 "hora_ini" => $dayStarCarbon,
    //                 "hora_fim" => $firstTimeCarbon
    //             ];
    //         }

    //         $hora_fim = $occupiedAgendas[0]["hora_fim"];
            
    //         $hora_ini = $occupiedAgendas[1]["hora_ini"];
    //         $hora_ini = Carbon::parse($hora_ini);
    //         $hora_fim = Carbon::parse($hora_fim);
    //         $intervalo = $hora_fim->diffInMinutes($hora_ini);
    //         if ($intervalo > 60) {
    //             $intervaloINT = $intervalo/60;
    //             // return $intervaloINT;
    //             for ($c = 0; $c < $intervaloINT; $c++) {
    //                 $disponiveis[] = [
    //                     "hora_ini" => $hora_fim->copy(),
    //                     "hora_fim" => $hora_fim->copy()->addHour(1)
    //                 ];
    //                 $hora_fim->addHour(1);
    //             }
    //         }

    //         // if ()
    //         // return $dayStart;
    //         return $disponiveis;
    // }



    
}

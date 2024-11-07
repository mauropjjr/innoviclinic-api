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

    
    public function getHorariosDisponiveis(array $data)
    {
        $dataHora = Carbon::parse($data["dataHora"]);
        $empresaConfig = EmpresaConfiguracao::where("empresa_id", $data["empresa_id"])->first();
        $agenda = Agenda::where('profissional_id', $data["profissional_id"])
        ->where('data', '>=', $dataHora->copy()->format("Y-m-d"))
        ->where('data', '<', $dataHora->copy()->addDays(3))
        // ->where("data", "<", $endDate)
        ->orderBy("data")
        ->orderBy("hora_ini")
        ->orderBy("hora_fim")
        ->get(['hora_ini', 'hora_fim', 'data'])
        ->toArray();

        // return $agenda;
        $dataAnterior = null;
        $datas = [];
        $datasMae = [];
        for ($c=0; $c < count($agenda); $c++) {
            $dataAtual = Carbon::parse($agenda[$c]["data"]);
            if ($dataAtual->greaterThan($dataAnterior)) {
                $datasMae[] = $datas;
                $datas = [];
            }
            $datas[] = [
                "hora_ini" => $agenda[$c]["hora_ini"],
                "hora_fim" => $agenda[$c]["hora_fim"],
                // "data" => $agenda[$c]["data"]
            ];
            $dataAnterior = Carbon::parse($agenda[$c]["data"]);

            if ($c == (count($agenda)-1)) {
                $datasMae[] = $datas;
            }
        }
        // return $datasMae;
        $response = [];
        // return $toCansadoEcomSono;
        for ($c=0; $c <= 2; $c++) {
            if ($datasMae[$c]) {
                $data["dataHora"] = Carbon::parse($data["dataHora"]);
                return $datasMae;
                $agendaDisponiveis = $this->getDisponiveis($data, $empresaConfig, $datasMae[$c]);
                $data["dataHora"]->addDay(1);
                $disponiveisProfissional = $this->filterProfissionalEscala($dataHora->copy(), $agendaDisponiveis, $data["profissional_id"]);
                $response[] = $disponiveisProfissional;
                $dataHora->addDay();
            }
        }
        return $response;
    }
    
    public function filterProfissionalEscala($data, array $disponiveis, int $profissional_id)
    {
        $data = $data->copy();
        $profissionalEscala = ProfissionalAgenda::where("profissional_id", $profissional_id)->where("dia", date('N', strtotime($data->copy())))->get()->toArray();

        $disponiveisProfissional = [];
        foreach ($profissionalEscala as $PE) {
            $iniProfissional = $data->copy()->setTimeFromTimeString($PE["hora_ini"]);
            $fimProfissional = $data->copy()->setTimeFromTimeString($PE["hora_fim"]);

            foreach ($disponiveis as $DI) {
                if ($DI["hora_ini"]->greaterThanOrEqualTo($iniProfissional) && $DI["hora_fim"]->lessThanOrEqualTo($fimProfissional)) {
                    $disponiveisProfissional[] = [
                        "hora_ini" => $DI["hora_ini"]->copy(),
                        "hora_fim" => $DI["hora_fim"]->copy()
                    ];
                }
            }

        }
        return $disponiveisProfissional;
    }
    
    public function getDisponiveis(array $data, $empresaConfig, $agenda)
    {
            if (count($agenda) == 0) {
                return [];
            }
            $dataHora = $data["dataHora"];
            
            $dayStart = $dataHora->setTimeFromTimeString($empresaConfig->hora_ini_agenda)->copy();
            $dayEnd = $dataHora->setTimeFromTimeString($empresaConfig->hora_fim_agenda)->copy();
            $occupiedAgendas = $agenda;
            
            for ($c=0; $c < count($occupiedAgendas); $c++) {
                $parsed = Carbon::parse($occupiedAgendas[$c]["hora_ini"]);
                $occupiedAgendas[$c]["hora_ini"] = $dataHora->setTimeFromTimeString($parsed->toTimeString())->copy();
                
                $parsed = Carbon::parse($occupiedAgendas[$c]["hora_fim"]);
                $occupiedAgendas[$c]["hora_fim"] = ($dataHora)->setTimeFromTimeString($parsed->toTimeString())->copy();
            }
            $primeiroIntervalo = $dayStart->diffInMinutes($occupiedAgendas[0]["hora_ini"]);
            $disponiveis = [];

            if ($primeiroIntervalo >= 60) {
                $disponiveis[] = [
                    "hora_ini" => $dataHora->setTimeFromTimeString($dayStart->toTimeString())->copy(),
                    "hora_fim" => $dataHora->setTimeFromTimeString($occupiedAgendas[0]["hora_ini"]->toTimeString())->copy(),
                ];
            }
            if (count($occupiedAgendas) > 1) {
                for ($c=1; $c < count($occupiedAgendas); $c++) {
                    $hora_fim = $occupiedAgendas[$c-1]["hora_fim"]->copy();
                    $hora_ini = $occupiedAgendas[$c]["hora_ini"]->copy();
                    $intervalo = $hora_fim->diffInMinutes($hora_ini);
                    
                    if ($intervalo > 60) {
                        $intervaloINT = $intervalo/60;
                        for ($d = 0; $d < $intervaloINT; $d++) {
                            $disponiveis[] = [
                                "hora_ini" => $hora_fim->copy(),
                                "hora_fim" => $hora_fim->addHour(1)->copy()
                            ];
                        }
                    }
                }
                $totalAgendas = count($occupiedAgendas);
                $lastHora_fim = $occupiedAgendas[$totalAgendas-1]["hora_fim"];
                $ultimoIntervalo = $lastHora_fim->diffInMinutes($dayEnd);
                if ($ultimoIntervalo >= 60) {
                    $ultimoIntervaloINT = $ultimoIntervalo/60;
                    for ($c = 0; $c < $ultimoIntervaloINT; $c++) {
                        $disponiveis[] = [
                            "hora_ini" => $lastHora_fim->copy(),
                            "hora_fim" => $lastHora_fim->addHour(1)->copy()
                        ];
                    }
                }
            }
            return $disponiveis;
    }


    
}

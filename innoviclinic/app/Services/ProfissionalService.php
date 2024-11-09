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
        // dd($this->gerarHorarios($empresaConfig));

        $agenda = Agenda::where('profissional_id', $data["profissional_id"])
            ->where('data', '>=', $dataHora->copy()->format("Y-m-d"))
              ->where('data', '<', $dataHora->copy()->addDays(15))
            // ->where("data", "<", $endDate)
            ->orderBy("data")
            ->orderBy("hora_ini")
            ->orderBy("hora_fim")
            ->get(['hora_ini', 'hora_fim', 'data'])
            ->toArray();

        $horarios = $this->gerarProximasDatas($data,  $dataHora, $empresaConfig->duracao_atendimento,  7);
        $response =  $this->removerHorariosAgendados($horarios, $agenda);


        return  $response ;
    }
    private function removerHorariosAgendados($horariosDisponiveis, $horariosAgendados) {
        $horariosFiltrados = [];

        foreach ($horariosDisponiveis as $horario) {
            $manterHorario = true;

            foreach ($horariosAgendados as $agendado) {
                $dataAgendada = Carbon::parse($agendado['data'])->format('Y-m-d');
                $horaIniAgendada = Carbon::parse($agendado['hora_ini'])->format('H:i');

                $dataHorario = Carbon::parse($horario)->format('Y-m-d');
                $horaHorario = Carbon::parse($horario)->format('H:i');

                // Verificar se a data e o horário coincidem com o horário agendado
                if ($dataHorario == $dataAgendada && $horaHorario == $horaIniAgendada) {
                    $manterHorario = false;
                    break;
                }
            }

            if ($manterHorario) {
                $horariosFiltrados[] = $horario;
            }
        }

        return $horariosFiltrados;
    }

    public function gerarProximasDatas($data,  $dataInicial, $intervalo, $dias  = 5)
    {
        $intervaloMinutos = Carbon::parse($intervalo)->hour * 60 + Carbon::parse($intervalo)->minute;
        $horariosSemana = ProfissionalAgenda::where("profissional_id", $data["profissional_id"])->where("empresa_id", $data["empresa_id"])->get()->toArray();
        $datasHorarios = [];
        $dataAtual = Carbon::parse($dataInicial);
        $diasGerados = 0;
        //TODO: Implementar feriado e eventos. Remover os dias de feriado e remover os eventos(evento tem data e hora inicial e final)

        while ($diasGerados <= $dias) {
            $diaSemana = $dataAtual->dayOfWeekIso; // Segunda=1, ..., Domingo=7

            // Verifica se há horários configurados para este dia da semana
            $horariosDia = array_filter($horariosSemana, fn($horario) => $horario['dia'] == $diaSemana);

            if (count($horariosDia) > 0) {
                // Gera horários para o dia atual baseado nos horários de funcionamento
                foreach ($horariosDia as $horario) {
                    $horaInicio = Carbon::parse($horario['hora_ini']);
                    $horaFim = Carbon::parse($horario['hora_fim']);

                    while ($horaInicio->lessThan($horaFim)) {
                        $datasHorarios[] = $dataAtual->format('Y-m-d') . ' ' . $horaInicio->format('H:i');
                        $horaInicio->addMinutes($intervaloMinutos);
                    }
                }

                $diasGerados++;
            }

            // Passa para o próximo dia
            $dataAtual->addDay();
        }

        return $datasHorarios;
    }
    // public function gerarHorarios($data, $empresaConfig)
    // {
    //     $horarios = [];

    //     // Converter a duração do atendimento para minutos
    //     $duracao_minutos = (int) explode(':', $empresaConfig->duracao_atendimento)[0] * 60;

    //     // Loop para os próximos 3 dias
    //     for ($i = 0; $i < 3; $i++) {
    //         // Obter a data atual no loop adicionando $i dias
    //         $data_atual = date('Y-m-d', strtotime($data . " + $i days"));

    //         // Inicializar o horário de início e fim do atendimento para o dia atual
    //         $hora_inicio = strtotime("$data_atual $empresaConfig->hora_ini_agenda");
    //         $hora_fim = strtotime("$data_atual $empresaConfig->hora_fim_agenda");

    //         // Loop para preencher os horários com a duração do atendimento
    //         while ($hora_inicio < $hora_fim) {
    //             $horarios[] = date('Y-m-d H:i', $hora_inicio);
    //             $hora_inicio = strtotime("+$duracao_minutos minutes", $hora_inicio);
    //         }
    //     }

    //     //$this->removerHorarios($horarios, $agendas);

    //     return $horarios;
    // }
    // public function  gerarProximasDatas($dataInicial, $intervalo, $quantidadeDias = 3) {
    //     $datasGeradas = [];

    //     // Converter a data inicial em um objeto Carbon
    //     $dataAtual = Carbon::parse($dataInicial);

    //     // Loop para gerar as próximas datas, até a quantidade de dias especificada
    //     for ($i = 0; $i < $quantidadeDias; $i++) {
    //         // Dia da semana em Carbon (1 = segunda, 2 = terça, ..., 7 = domingo)
    //         $diaSemana = $dataAtual->dayOfWeekIso;

    //         // Filtra os horários para o dia da semana atual
    //         $horariosDoDia = array_filter($horarios, function($horario) use ($diaSemana) {
    //             return $horario['dia'] == $diaSemana;
    //         });

    //         // Para cada horário do dia, gera um datetime e adiciona à lista de datas
    //         foreach ($horariosDoDia as $horario) {
    //             $horaInicio = Carbon::parse("{$dataAtual->format('Y-m-d')} {$horario['hora_ini']}");
    //             $horaFim = Carbon::parse("{$dataAtual->format('Y-m-d')} {$horario['hora_fim']}");

    //             // Gera os horários entre o início e o fim de acordo com o intervalo
    //             while ($horaInicio < $horaFim) {
    //                 $datasGeradas[] = $horaInicio->format('Y-m-d H:i');
    //                 $horaInicio->addMinutes($intervalo); // Adiciona o intervalo especificado
    //             }
    //         }

    //         // Incrementa para o próximo dia
    //         $dataAtual->addDay();
    //     }

    //     return $datasGeradas;
    // }
    // public function filterProfissionalEscala($data, array $disponiveis, int $profissional_id)
    // {
    //     $data = $data->copy();
    //     $profissionalEscala = ProfissionalAgenda::where("profissional_id", $profissional_id)->where("dia", date('N', strtotime($data->copy())))->get()->toArray();

    //     $disponiveisProfissional = [];
    //     foreach ($profissionalEscala as $PE) {
    //         $iniProfissional = $data->copy()->setTimeFromTimeString($PE["hora_ini"]);
    //         $fimProfissional = $data->copy()->setTimeFromTimeString($PE["hora_fim"]);

    //         foreach ($disponiveis as $DI) {
    //             if ($DI["hora_ini"]->greaterThanOrEqualTo($iniProfissional) && $DI["hora_fim"]->lessThanOrEqualTo($fimProfissional)) {
    //                 $disponiveisProfissional[] = [
    //                     "hora_ini" => $DI["hora_ini"]->copy(),
    //                     "hora_fim" => $DI["hora_fim"]->copy()
    //                 ];
    //             }
    //         }
    //     }
    //     return $disponiveisProfissional;
    // }

    // public function getDisponiveis(array $data, $empresaConfig, $agenda)
    // {
    //     if (count($agenda) == 0) {
    //         return [];
    //     }
    //     $dataHora = $data["dataHora"];

    //     $dayStart = $dataHora->setTimeFromTimeString($empresaConfig->hora_ini_agenda)->copy();
    //     $dayEnd = $dataHora->setTimeFromTimeString($empresaConfig->hora_fim_agenda)->copy();
    //     $occupiedAgendas = $agenda;

    //     for ($c = 0; $c < count($occupiedAgendas); $c++) {
    //         $parsed = Carbon::parse($occupiedAgendas[$c]["hora_ini"]);
    //         $occupiedAgendas[$c]["hora_ini"] = $dataHora->setTimeFromTimeString($parsed->toTimeString())->copy();

    //         $parsed = Carbon::parse($occupiedAgendas[$c]["hora_fim"]);
    //         $occupiedAgendas[$c]["hora_fim"] = ($dataHora)->setTimeFromTimeString($parsed->toTimeString())->copy();
    //     }
    //     $primeiroIntervalo = $dayStart->diffInMinutes($occupiedAgendas[0]["hora_ini"]);
    //     $disponiveis = [];

    //     if ($primeiroIntervalo >= 60) {
    //         $disponiveis[] = [
    //             "hora_ini" => $dataHora->setTimeFromTimeString($dayStart->toTimeString())->copy(),
    //             "hora_fim" => $dataHora->setTimeFromTimeString($occupiedAgendas[0]["hora_ini"]->toTimeString())->copy(),
    //         ];
    //     }
    //     if (count($occupiedAgendas) > 1) {
    //         for ($c = 1; $c < count($occupiedAgendas); $c++) {
    //             $hora_fim = $occupiedAgendas[$c - 1]["hora_fim"]->copy();
    //             $hora_ini = $occupiedAgendas[$c]["hora_ini"]->copy();
    //             $intervalo = $hora_fim->diffInMinutes($hora_ini);

    //             if ($intervalo > 60) {
    //                 $intervaloINT = $intervalo / 60;
    //                 for ($d = 0; $d < $intervaloINT; $d++) {
    //                     $disponiveis[] = [
    //                         "hora_ini" => $hora_fim->copy(),
    //                         "hora_fim" => $hora_fim->addHour(1)->copy()
    //                     ];
    //                 }
    //             }
    //         }
    //         $totalAgendas = count($occupiedAgendas);
    //         $lastHora_fim = $occupiedAgendas[$totalAgendas - 1]["hora_fim"];
    //         $ultimoIntervalo = $lastHora_fim->diffInMinutes($dayEnd);
    //         if ($ultimoIntervalo >= 60) {
    //             $ultimoIntervaloINT = $ultimoIntervalo / 60;
    //             for ($c = 0; $c < $ultimoIntervaloINT; $c++) {
    //                 $disponiveis[] = [
    //                     "hora_ini" => $lastHora_fim->copy(),
    //                     "hora_fim" => $lastHora_fim->addHour(1)->copy()
    //                 ];
    //             }
    //         }
    //     }

    //     return $disponiveis;
    // }
}

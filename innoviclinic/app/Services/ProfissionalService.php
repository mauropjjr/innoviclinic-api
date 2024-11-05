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
        // $data["empresa_id"]= 1;
        // $data["profissional_id"]= 1;
        $empresaConfig = EmpresaConfiguracao::where("empresa_id", $data["empresa_id"])->first();
        $dayStart = $empresaConfig->hora_ini_agenda;
        $dayEnd = $empresaConfig->hora_fim_agenda;
        list($hours, $minutes) = explode(":", $empresaConfig->duracao_atendimento);
        $interval = ($hours * 60) + $minutes;
    
        $date = Carbon::parse($data["dataHora"]);
        $occupiedAgendas = Agenda::where('profissional_id', $data["profissional_id"])
            ->whereBetween('data', [$date, $date->addDays(2)])
            ->get(['hora_ini', 'hora_fim']);
    
        $currentTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $dayStart);
        $endTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $dayEnd);
    
        $occupiedTimes = $occupiedAgendas->map(function ($agenda) use ($date) {
            return [
                'start' => Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $agenda->hora_ini),
                'end' => Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $agenda->hora_fim),
            ];
        });
    
        $availableSlots = [];
        while ($currentTime->lt($endTime)) {
            $slotEndTime = $currentTime->copy()->addMinutes($interval);
            $isAvailable = !$occupiedTimes->contains(function ($occupied) use ($currentTime, $slotEndTime) {
                return $currentTime->lt($occupied['end']) && $slotEndTime->gt($occupied['start']);
            });
    
            if ($isAvailable) {
                $availableSlots[] = [
                    'start' => $currentTime->format('H:i'),
                    'end' => $slotEndTime->format('H:i'),
                ];
            }
    
            $currentTime->addMinutes($interval);
        }
    
        return $this->compareAvailabilityWithWorkHours($data["profissional_id"], $availableSlots);
    }
    
    public function compareAvailabilityWithWorkHours($profissionalId, $availableSlots)
    {
        $workHours = ProfissionalAgenda::where("profissional_id", $profissionalId)
            ->get(["hora_ini", "hora_fim"]);
    
        return collect($availableSlots)->filter(function ($slot) use ($workHours) {
            $slotStart = Carbon::createFromFormat('H:i', $slot['start']);
            $slotEnd = Carbon::createFromFormat('H:i', $slot['end']);
    
            return $workHours->contains(function ($workHour) use ($slotStart, $slotEnd) {
                $workStart = Carbon::createFromFormat('H:i', $workHour->hora_ini);
                $workEnd = Carbon::createFromFormat('H:i', $workHour->hora_fim);
    
                return $slotStart >= $workStart && $slotEnd <= $workEnd;
            });
        })->values()->all();
    }
    
}

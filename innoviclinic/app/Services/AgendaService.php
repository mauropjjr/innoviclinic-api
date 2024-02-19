<?php

namespace App\Services;

use App\Helpers\Utils;
use App\Models\Agenda;
use App\Enums\AgendaStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AgendaValidationException;
use Symfony\Component\HttpFoundation\Response;

class AgendaService
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;
    }

    public function verificarSeDataHoraAgendamentoEstaLivre(array $data, int $agenda_id = null)
    {
        // Verificar se já existe um agendamento que se sobrepõe ao novo agendamento
        $query = Agenda::where('empresa_id', $data['empresa_id'])
            ->where('profissional_id', $data['profissional_id'])
            ->where('sala_id', $data['sala_id'])
            ->where('data', $data['data'])
            ->whereNotIn('agenda_status_id', [AgendaStatusEnum::CANCELADO, AgendaStatusEnum::FALTOU]) // Excluir os status Cancelado (3) e Faltou (7)
            ->where(function ($query) use ($data) {
                $query->where(function ($query) use ($data) {
                    $query->where('hora_ini', '>=', $data['hora_ini'])
                        ->where('hora_ini', '<', $data['hora_fim']);
                })
                    ->orWhere(function ($query) use ($data) {
                        $query->where('hora_fim', '>', $data['hora_ini'])
                            ->where('hora_fim', '<=', $data['hora_fim']);
                    });
            });

        // Excluir a agenda atual se estiver sendo alterada
        if ($agenda_id !== null) {
            $query->where('id', '!=', $agenda_id);
        }

        $existingAgenda = $query->exists();
        return ['success' => !$existingAgenda, 'message' => $existingAgenda ? 'Horário indisponível para agendamento.' : null];
    }

    public function verificacoesDisponibilidade(array $data, int $agenda_id = null)
    {
        // Verificar se o horário de agendamento está livre
        $result = $this->verificarSeDataHoraAgendamentoEstaLivre($data, $agenda_id);
        if (!$result['success']) {
            throw new AgendaValidationException($result['message']);
        }

        // Verificar se o profissional está com esse dia e horário parametrizado
        $result = (new ProfissionalAgendaService())->verificarSeEstaDisponivelNesteDiaHorario($data);
        if (!$result['success']) {
            throw new AgendaValidationException($result['message']);
        }

        // Verificar se o profissional não tem eventos para participar
        $result = (new EventoService())->verificarSeNaoHaEventosNesteDiaHorario($data);
        if (!$result['success']) {
            throw new AgendaValidationException($result['message']);
        }

        // Verificar se não é um feriado
        $result = (new FeriadoService())->verificarSeDiaFeriado($data);
        if (!$result['success']) {
            throw new AgendaValidationException($result['message']);
        }
    }

    public function createAgenda(array $data)
    {
        try {
            DB::beginTransaction();

            $user = $this->customAuth->getUser();
            $data['empresa_id'] = $user->empresa_profissional->empresa_id;

            $this->verificacoesDisponibilidade($data, null);

            // Criar a agenda
            $data['agenda_status_id'] = !empty($data['agenda_status_id']) ? $data['agenda_status_id'] : AgendaStatusEnum::CONFIRMAR;
            $data['duracao_min'] = Utils::calcularDiferencaMinutos($data['hora_ini'], $data['hora_fim']);
            $agenda = Agenda::create($data);

            // Criar o prontuário se não existir
            (new ProntuarioService())->createIfNotExists($data);

            DB::commit();

            return response()->json($agenda);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateAgenda(array $data, int $agenda_id)
    {
        try {
            DB::beginTransaction();

            $user = $this->customAuth->getUser();
            $data['empresa_id'] = $user->empresa_profissional->empresa_id;

            // Verificar se a agenda existe
            $agenda = Agenda::findOrFail($agenda_id);

            $this->verificacoesDisponibilidade($data, $agenda_id);

            // Atualizar os dados da agenda
            $data['duracao_min'] = Utils::calcularDiferencaMinutos($data['hora_ini'], $data['hora_fim']);
            $agenda->update($data);

            // Criar o prontuário se não existir
            (new ProntuarioService())->createIfNotExists($data);

            DB::commit();

            return response()->json($agenda);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}

<?php

namespace App\Services;

use App\Helpers\Utils;
use App\Models\Agenda;
use App\Enums\AgendaStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AgendaValidationException;
use App\Models\AgendaProcedimento;
use App\Models\Profissional;

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

        if(!empty($data['sala_id'])){
            $query->where('sala_id', $data['sala_id']);
        }

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

    public function create(array $data) : Agenda
    {
        try {
            DB::beginTransaction();

            if (is_null($data["paciente_id"]) || $data["paciente_id"] == "") {
                $data = $this->createUndefinedPacient($data);
            }

            $user = $this->customAuth->getUser();
            $data['empresa_id'] = $user->empresa_profissional->empresa_id;

            $this->verificacoesDisponibilidade($data, null);

            // Criar a agenda
            $data['agenda_status_id'] = !empty($data['agenda_status_id']) ? $data['agenda_status_id'] : AgendaStatusEnum::CONFIRMAR;
            $data['duracao_min'] = Utils::calcularDiferencaMinutos($data['hora_ini'], $data['hora_fim']);
            $agenda = Agenda::create($data);

            // Adicionar os procedimentos associados na agenda
            $procedimentosData = isset($data['procedimentos']) ? $data['procedimentos'] : null;
            if (isset($procedimentosData) && is_array($procedimentosData)) {
                $procedimentos = [];
                foreach ($procedimentosData as $value) {
                    $procedimentos[] = new AgendaProcedimento(['procedimento_id' => $value['procedimento_id'], 'qtde' => $value['qtde'], 'valor' => $value['valor']]);
                }
                $agenda->agenda_procedimentos()->saveMany($procedimentos);
            }

            // Criar o prontuário se não existir
            (new ProntuarioService())->createIfNotExists($data);

            DB::commit();

            return $agenda;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createUndefinedPacient(array $data)
    {
        $paciente = (new PacienteService($this->customAuth))->create([
            "celular" => $data["celular"],
            "nome" => $data["nome"],
            "usuario_id" => $data["profissional_id"]
        ]);
        $data["paciente_id"] = $paciente->pessoa_id;
        return $data;
    }

    public function update(array $data, int $agenda_id)
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

            // Obter os dados dos procedimentos
            $procedimentosData = isset($data['procedimentos']) ? $data['procedimentos'] : null;

            // Sincronizar os procedimentos associados na agenda
            if (isset($procedimentosData) && is_array($procedimentosData)) {
                $procedimentoIds = [];
                foreach ($procedimentosData as $value) {
                    $procedimento = AgendaProcedimento::updateOrCreate([
                        'agenda_id' => $agenda->id,
                        'procedimento_id' => $value['procedimento_id'],
                    ], [
                        'qtde' => $value['qtde'],
                        'valor' => $value['valor'],
                    ]);
                    $procedimentoIds[] = $procedimento->id;
                }

                // Remover procedimentos não presentes na lista
                $agenda->agenda_procedimentos()->whereNotIn('id', $procedimentoIds)->delete();
            }

            // Criar o prontuário se não existir
            (new ProntuarioService())->createIfNotExists($data);

            DB::commit();

            return response()->json($agenda);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function list(array $filtros)
    {
        $user = $this->customAuth->getUser();
        $filtros['empresa_id'] = $user->empresa_profissional->empresa_id;

        // Lógica para buscar as agendas com base nos filtros
        $agendas = Agenda::select(
            'agendas.id',
            DB::raw("'agenda' as tipo"),
            'agendas.empresa_id',
            'agendas.profissional_id',
            'agendas.sala_id',
            'agendas.paciente_id',
            'prontuarios.id as prontuario_id',
            'agenda_tipos.nome as agenda_tipo',
            'agenda_tipos.cor',
            'agendas.nome',
            'agendas.data',
            'agendas.hora_ini',
            'agendas.hora_fim',
            'agendas.data as data_ini',
            'agendas.data as data_fim',
            DB::raw('NULL as dias_semana'),
            'agenda_tipos.sem_horario',
            'agendas.primeiro_atend'
        )
            ->leftJoin('agenda_tipos', 'agendas.agenda_tipo_id', '=', 'agenda_tipos.id')
            ->leftJoin('prontuarios', function ($join) {
                $join->on('agendas.empresa_id', '=', 'prontuarios.empresa_id')
                    ->on('agendas.profissional_id', '=', 'prontuarios.profissional_id')
                    ->on('agendas.paciente_id', '=', 'prontuarios.paciente_id');
            })
            ->where('agendas.empresa_id', $filtros['empresa_id'])
            ->where('agendas.profissional_id', $filtros['profissional_id'])
            ->whereBetween('agendas.data', [$filtros['data_inicio'], $filtros['data_fim']]);

        if(!empty($filtros['sala_id'])){
            $agendas->where('agendas.sala_id', $filtros['sala_id']);
        }

        if(!empty($filtros['nome_agenda'])){
            $agendas->where('agendas.nome', 'LIKE', "%" . $filtros['nome_agenda']."%");
        }

        if (isset($filtros['exibir_todos_status']) && !$filtros['exibir_todos_status']) {
            $agendas->whereNotIn('agendas.agenda_status_id', [AgendaStatusEnum::FALTOU, AgendaStatusEnum::CANCELADO]);
        }

        $apenas_agenda = isset($filtros['apenas_agenda']) && $filtros['apenas_agenda'] ? true : false;
        $agendas = $agendas->get();
        $resultados = $agendas;

        if(!$apenas_agenda){
            $eventos = (new EventoService())->listAgenda($filtros);
            $feriados = (new FeriadoService())->listAgenda($filtros);
            // Mesclar os resultados
            $resultados = $eventos->concat($agendas)->concat($feriados);
        }

        return $resultados;
    }
}

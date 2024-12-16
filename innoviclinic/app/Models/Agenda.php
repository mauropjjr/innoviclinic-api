<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use App\Traits\AutoSetParamsAgendaDefault;
use App\Traits\AutoSetUsuarioId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Agenda
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
 * @property int $sala_id
 * @property int $paciente_id
 * @property int $convenio_id
 * @property int $agenda_tipo_id
 * @property int $agenda_status_id
 * @property string $nome
 * @property string $celular
 * @property string|null $telefone
 * @property string|null $email
 * @property int $primeiro_atend
 * @property int $enviar_msg
 * @property Carbon $data
 * @property string $hora_ini
 * @property string $hora_fim
 * @property int $duracao_min
 * @property int $telemedicina
 * @property float $valor
 * @property string|null $hora_chegada
 * @property string|null $hora_atendimento_ini
 * @property string|null $hora_atendimento_fim
 * @property string|null $observacoes
 * @property string|null $motivo_cancelamento
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property AgendaStatus $agenda_status
 * @property Convenio $convenio
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 * @property Sala $sala
 * @property Collection|Procedimento[] $procedimentos
 * @property Collection|Interaco[] $interacos
 *
 * @package App\Models
 */
class Agenda extends Model
{
    use AutoSetUsuarioId;
    use AutoSetParamsAgendaDefault;
	use HasFactory;
	protected $table = 'agendas';

	protected $casts = [
		'empresa_id' => 'int',
		'profissional_id' => 'int',
		'sala_id' => 'int',
		'paciente_id' => 'int',
		'convenio_id' => 'int',
		'agenda_status_id' => 'int',
		'primeiro_atend' => 'int',
		'enviar_msg' => 'int',
		'data' => 'datetime',
		'duracao_min' => 'int',
		'telemedicina' => 'int',
		'valor' => 'float',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'senha',
        'remember_token',
        'usuario_id',
        'created_at',
        'updated_at',
    ];

	protected $fillable = [
		'empresa_id',
		'profissional_id',
		'sala_id',
		'paciente_id',
		'convenio_id',
        'agenda_tipo_id',
		'agenda_status_id',
		'nome',
		'celular',
		'telefone',
		'email',
		'primeiro_atend',
		'enviar_msg',
		'data',
		'hora_ini',
		'hora_fim',
		'duracao_min',
		'telemedicina',
		'valor',
		'hora_chegada',
		'hora_atendimento_ini',
		'hora_atendimento_fim',
		'observacoes',
		'motivo_cancelamento',
		'usuario_id'
	];

	protected static function boot() {
		parent::boot();
		static::saving(function ($agenda) {
			self::handleAgendaStatus($agenda);
		});
		
		static::updating(function ($agenda) {
			self::handleAgendaStatus($agenda);
		});
	}

	private static function handleAgendaStatus(Agenda $agenda) 
	{
		$agenda_status_id = $agenda->agenda_status_id ?? 0;

		$status = $agenda_status_id > 0 ? AgendaStatus::find($agenda_status_id) : null;
		$isActive = $status?->ativo ?? 0;
	
		if ($isActive > 0) {
			self::setAgendaStatusActions($agenda_status_id, $agenda);
		}
	}

	private static function setAgendaStatusActions($agenda_status_id, Agenda $agenda)
	{
		$hora_atual = Carbon::now()->format('H:i');
		//chegou
		if ($agenda_status_id == 4) {
			$agenda->hora_chegada = $hora_atual;
		}
		//em  atendimento
		else if ($agenda_status_id == 5) {
			$agenda->hora_atendimento_ini = $hora_atual;
		}
		//atendido
		else if ($agenda_status_id == 6) {
			$agenda->hora_atendimento_fim = $hora_atual;
		}
		//cancelado
		else if ($agenda_status_id == 3) {
			$agenda->motivo_cancelamento = "Cancelado às: " . Carbon::now()->toDateTimeString();
		}
	}

	public function agenda_tipo()
	{
		return $this->belongsTo(AgendaTipo::class);
	}

    public function agenda_status()
	{
		return $this->belongsTo(AgendaStatus::class);
	}

	public function convenio()
	{
		return $this->belongsTo(Convenio::class);
	}

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function profissional()
	{
		return $this->belongsTo(Pessoa::class, 'profissional_id');
	}

    public function paciente()
	{
		return $this->belongsTo(Pessoa::class, 'paciente_id');
	}

	public function sala()
	{
		return $this->belongsTo(Sala::class);
	}

	public function procedimentos()
	{
		return $this->belongsToMany(Procedimento::class, 'agenda_procedimentos', 'agenda_id', 'id')
					->withPivot('procedimento_id', 'qtde', 'valor', 'usuario_id')
					->withTimestamps();
	}

    public function agenda_procedimentos()
	{
		return $this->hasMany(AgendaProcedimento::class, 'agenda_id');
	}

    public function prontuario()
    {
        return $this->hasOne(Prontuario::class, 'paciente_id', 'paciente_id');
    }

    public function prontuarios()
    {
        return $this->hasMany(Prontuario::class, 'paciente_id', 'paciente_id');
    }

    public static function getStatusAttribute($value)
    {
        return AgendaStatus::from($value)->id;
    }
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Agenda
 * 
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
 * @property int $sala_id
 * @property int $paciente_id
 * @property int $convenio_id
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

	protected $fillable = [
		'empresa_id',
		'profissional_id',
		'sala_id',
		'paciente_id',
		'convenio_id',
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

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'profissional_id');
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

	public function interacos()
	{
		return $this->hasMany(Interacao::class);
	}
}

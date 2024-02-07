<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\AutoSetControleInteracao;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Interaco
 *
 * @property int $id
 * @property int $prontuario_id
 * @property int|null $agenda_id
 * @property Carbon $data
 * @property string $hora_ini
 * @property string|null $hora_fim
 * @property string|null $tempo_atendimento
 * @property int $finalizado
 * @property int $teleatendimento
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Agenda|null $agenda
 * @property Prontuario $prontuario
 * @property Pessoa $pessoa
 * @property Collection|InteracaoAtendimento[] $interacao_atendimentos
 *
 * @package App\Models
 */
class Interacao extends Model
{
    use AutoSetControleInteracao;

	protected $table = 'interacoes';

	protected $casts = [
		'prontuario_id' => 'int',
		'agenda_id' => 'int',
		'data' => 'datetime',
		'finalizado' => 'int',
		'teleatendimento' => 'int',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
    ];

	protected $fillable = [
		'prontuario_id',
		'agenda_id',
		'data',
		'hora_ini',
		'hora_fim',
		'tempo_atendimento',
		'finalizado',
		'teleatendimento',
		'usuario_id'
	];

	public function agenda()
	{
		return $this->belongsTo(Agenda::class);
	}

	public function prontuario()
	{
		return $this->belongsTo(Prontuario::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}

	public function interacao_atendimentos()
	{
		return $this->hasMany(InteracaoAtendimento::class, 'interacao_id');
	}
}

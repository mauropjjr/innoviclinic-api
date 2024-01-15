<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Procedimento
 * 
 * @property int $id
 * @property int $empresa_id
 * @property int $procedimento_tipo_id
 * @property string $nome
 * @property string $cor
 * @property int $duracao_min
 * @property float $valor
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 * @property Procedimento $procedimento
 * @property Collection|Agenda[] $agendas
 * @property Collection|ProcedimentoConvenio[] $procedimento_convenios
 * @property Collection|Procedimento[] $procedimentos
 *
 * @package App\Models
 */
class Procedimento extends Model
{
	protected $table = 'procedimentos';

	protected $casts = [
		'empresa_id' => 'int',
		'procedimento_tipo_id' => 'int',
		'duracao_min' => 'int',
		'valor' => 'float',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'empresa_id',
		'procedimento_tipo_id',
		'nome',
		'cor',
		'duracao_min',
		'valor',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}

	public function procedimento()
	{
		return $this->belongsTo(Procedimento::class, 'procedimento_tipo_id');
	}

	public function agendas()
	{
		return $this->belongsToMany(Agenda::class, 'agenda_procedimentos', 'id')
					->withPivot('procedimento_id', 'qtde', 'valor', 'usuario_id')
					->withTimestamps();
	}

	public function procedimento_convenios()
	{
		return $this->hasMany(ProcedimentoConvenio::class);
	}

	public function procedimentos()
	{
		return $this->hasMany(Procedimento::class, 'procedimento_tipo_id');
	}
}

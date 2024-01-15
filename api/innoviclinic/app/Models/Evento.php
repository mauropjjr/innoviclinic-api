<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Evento
 * 
 * @property int $id
 * @property int $empresa_id
 * @property string $nome
 * @property Carbon $data_ini
 * @property Carbon $data_fim
 * @property string|null $descricao
 * @property string $hora_ini
 * @property string $hora_fim
 * @property string|null $dias_semana
 * @property string $cor
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 * @property Collection|EventoProfissionai[] $evento_profissionais
 *
 * @package App\Models
 */
class Evento extends Model
{
	protected $table = 'eventos';

	protected $casts = [
		'empresa_id' => 'int',
		'data_ini' => 'datetime',
		'data_fim' => 'datetime',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'empresa_id',
		'nome',
		'data_ini',
		'data_fim',
		'descricao',
		'hora_ini',
		'hora_fim',
		'dias_semana',
		'cor',
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

	public function evento_profissionais()
	{
		return $this->hasMany(EventoProfissionai::class);
	}
}

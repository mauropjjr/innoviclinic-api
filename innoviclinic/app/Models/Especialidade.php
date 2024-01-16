<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Especialidade
 *
 * @property int $id
 * @property int $profissao_id
 * @property string $nome
 * @property int $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Profissao $profissao
 * @property Pessoa $pessoa
 * @property Collection|ProfissionalEspecialidade[] $profissional_especialidades
 *
 * @package App\Models
 */
class Especialidade extends Model
{
	protected $table = 'especialidades';

	protected $casts = [
		'profissao_id' => 'int',
		'ativo' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'profissao_id',
		'nome',
		'ativo',
		'usuario_id'
	];

	public function profissao()
	{
		return $this->belongsTo(Profissao::class, 'profissao_id');
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}

	public function profissional_especialidades()
	{
		return $this->hasMany(ProfissionalEspecialidade::class);
	}
}

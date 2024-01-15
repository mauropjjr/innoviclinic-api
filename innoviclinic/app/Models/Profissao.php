<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Profisso
 * 
 * @property int $id
 * @property string $nome
 * @property int $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Pessoa $pessoa
 * @property Collection|Especialidade[] $especialidades
 * @property Collection|Profissionai[] $profissionais
 *
 * @package App\Models
 */
class Profissao extends Model
{
	protected $table = 'profissoes';

	protected $casts = [
		'ativo' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'nome',
		'ativo',
		'usuario_id'
	];

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}

	public function especialidades()
	{
		return $this->hasMany(Especialidade::class, 'profissao_id');
	}

	public function profissionais()
	{
		return $this->hasMany(Profissional::class, 'profissao_id');
	}
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcedimentoTipo
 * 
 * @property int $id
 * @property string $nome
 * @property int $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class ProcedimentoTipo extends Model
{
	protected $table = 'procedimento_tipos';

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
}

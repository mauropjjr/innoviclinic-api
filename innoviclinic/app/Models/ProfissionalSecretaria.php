<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfissionalSecretaria
 * 
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
 * @property int $secretaria_id
 * @property bool $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class ProfissionalSecretaria extends Model
{
	protected $table = 'profissional_secretarias';

	protected $casts = [
		'empresa_id' => 'int',
		'profissional_id' => 'int',
		'secretaria_id' => 'int',
		'ativo' => 'bool',
		'usuario_id' => 'int'
	];

	protected $hidden = [
		'secretaria_id'
	];

	protected $fillable = [
		'empresa_id',
		'profissional_id',
		'secretaria_id',
		'ativo',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'secretaria_id');
	}
}

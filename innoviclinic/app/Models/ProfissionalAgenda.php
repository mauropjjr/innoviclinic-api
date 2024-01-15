<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfissionalAgenda
 * 
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
 * @property int $dia
 * @property string $hora_ini
 * @property string $hora_fim
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class ProfissionalAgenda extends Model
{
	protected $table = 'profissional_agendas';

	protected $casts = [
		'empresa_id' => 'int',
		'profissional_id' => 'int',
		'dia' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'empresa_id',
		'profissional_id',
		'dia',
		'hora_ini',
		'hora_fim',
		'usuario_id'
	];
}

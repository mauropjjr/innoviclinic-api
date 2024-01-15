<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventoProfissionai
 * 
 * @property int $id
 * @property int $evento_id
 * @property int $profissional_id
 * @property int $usuario_id
 * @property Carbon $created_at
 * 
 * @property Evento $evento
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class EventoProfissional extends Model
{
	protected $table = 'evento_profissionais';
	public $timestamps = false;

	protected $casts = [
		'evento_id' => 'int',
		'profissional_id' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'evento_id',
		'profissional_id',
		'usuario_id'
	];

	public function evento()
	{
		return $this->belongsTo(Evento::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}
}

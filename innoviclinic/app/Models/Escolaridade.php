<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Escolaridade
 *
 * @property int $id
 * @property string $nome
 * @property int $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Pessoa $pessoa
 * @property Collection|Paciente[] $pacientes
 *
 * @package App\Models
 */
class Escolaridade extends Model
{
	protected $table = 'escolaridades';

	protected $casts = [
		'ativo' => 'int',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
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

	public function pacientes()
	{
		return $this->hasMany(Paciente::class);
	}
}

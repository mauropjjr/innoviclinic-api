<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoSetEmpresaIdUsuarioId;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Sala
 *
 * @property int $id
 * @property int $empresa_id
 * @property string $nome
 * @property int $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 * @property Collection|Agenda[] $agendas
 *
 * @package App\Models
 */
class Sala extends Model
{
    use AutoSetEmpresaIdUsuarioId;

	protected $table = 'salas';

	protected $casts = [
		'empresa_id' => 'int',
		'ativo' => 'int',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
    ];

	protected $fillable = [
		'empresa_id',
		'nome',
		'ativo',
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

	public function agendas()
	{
		return $this->hasMany(Agenda::class);
	}
}

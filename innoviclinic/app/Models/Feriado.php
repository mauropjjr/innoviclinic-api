<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoSetEmpresaIdUsuarioId;

/**
 * Class Feriado
 *
 * @property int $id
 * @property int|null $empresa_id
 * @property Carbon $data
 * @property string $nome
 * @property string $descricao
 * @property string $tipo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Empresa|null $empresa
 *
 * @package App\Models
 */
class Feriado extends Model
{
    use AutoSetEmpresaIdUsuarioId;

	protected $table = 'feriados';

	protected $casts = [
		'empresa_id' => 'int',
		'data' => 'datetime',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
    ];

	protected $fillable = [
		'empresa_id',
		'data',
		'nome',
		'descricao',
		'tipo',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}
}

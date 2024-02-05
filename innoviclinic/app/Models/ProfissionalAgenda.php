<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoSetEmpresaIdProfissionalIdUsuarioId;

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
    use AutoSetEmpresaIdProfissionalIdUsuarioId;

	protected $table = 'profissional_agendas';

	protected $casts = [
		'empresa_id' => 'int',
		'profissional_id' => 'int',
		'dia' => 'int',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
    ];

	protected $fillable = [
		'empresa_id',
		'profissional_id',
		'dia',
		'hora_ini',
		'hora_fim',
		'usuario_id'
	];

    public function profissional()
	{
		return $this->belongsTo(Pessoa::class, 'profissional_id');
	}
}

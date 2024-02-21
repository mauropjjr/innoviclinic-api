<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoSetEmpresaIdUsuarioId;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AgendaTipo
 *
 * @property int $id
 * @property int|null $empresa_id
 * @property string $nome
 * @property int|null $ativo
 * @property int $sem_horario
 * @property int $sem_procedimento
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property Empresa|null $empresa
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class AgendaTipo extends Model
{
    use AutoSetEmpresaIdUsuarioId;
    use HasFactory;
	protected $table = 'agenda_tipos';

	protected $casts = [
		'id' => 'int',
		'empresa_id' => 'int',
		'ativo' => 'int',
		'sem_horario' => 'int',
		'sem_procedimento' => 'int',
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
        'cor',
		'ativo',
		'sem_horario',
		'sem_procedimento',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function usuario()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}
}

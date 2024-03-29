<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\AutoSetUsuarioId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfissionalEspecialidade
 *
 * @property int $id
 * @property int $profissional_id
 * @property int $especialidade_id
 * @property int $usuario_id
 * @property Carbon|null $created_at
 *
 * @property Especialidade $especialidade
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class ProfissionalEspecialidade extends Model
{
    use AutoSetUsuarioId;

	protected $table = 'profissional_especialidades';
	public $timestamps = false;

	protected $casts = [
		'profissional_id' => 'int',
		'especialidade_id' => 'int',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
    ];

	protected $fillable = [
		'profissional_id',
		'especialidade_id',
		'usuario_id'
	];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class, 'profissional_id');
    }

	public function especialidade()
	{
		return $this->belongsTo(Especialidade::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'pessoa_id');
	}
}

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
 * Class Evento
 *
 * @property int $id
 * @property int $empresa_id
 * @property string $nome
 * @property Carbon $data_ini
 * @property Carbon $data_fim
 * @property string|null $descricao
 * @property string $hora_ini
 * @property string $hora_fim
 * @property string|null $dias_semana
 * @property string $cor
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 * @property Collection|EventoProfissionai[] $evento_profissionais
 *
 * @package App\Models
 */
class Evento extends Model
{
    use AutoSetEmpresaIdUsuarioId;

	protected $table = 'eventos';

	protected $casts = [
		'empresa_id' => 'int',
		'data_ini' => 'datetime',
		'data_fim' => 'datetime',
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
		'data_ini',
		'data_fim',
		'descricao',
		'hora_ini',
		'hora_fim',
		'dias_semana',
		'cor',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function evento_profissionais()
	{
		return $this->hasMany(EventoProfissional::class);
	}

    public function profissionais()
    {
        return $this->belongsToMany(
            EventoProfissional::class,
            'evento_profissionais',
            'evento_id',
            'profissional_id'
        )->withPivot('usuario_id', 'created_at');
    }
}

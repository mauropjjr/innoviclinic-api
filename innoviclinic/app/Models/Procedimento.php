<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use App\Traits\AutoSetEmpresaIdUsuarioId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Procedimento
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $procedimento_tipo_id
 * @property string $nome
 * @property int $duracao_min
 * @property float $valor
 * @property string $preparacao
 * @property string $observacao
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 * @property Procedimento $procedimento
 * @property Collection|Agenda[] $agendas
 * @property Collection|ProcedimentoConvenio[] $procedimento_convenios
 * @property Collection|Procedimento[] $procedimentos
 *
 * @package App\Models
 */
class Procedimento extends Model
{
    use AutoSetEmpresaIdUsuarioId;

	protected $table = 'procedimentos';

	protected $casts = [
		'empresa_id' => 'int',
		'procedimento_tipo_id' => 'int',
		'duracao_min' => 'int',
		'valor' => 'float',
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
		'procedimento_tipo_id',
		'nome',
		'duracao_min',
		'valor',
        'preparacao',
        'observacao',
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

	public function procedimento_tipo()
	{
		return $this->belongsTo(ProcedimentoTipo::class, 'procedimento_tipo_id');
	}

	public function agendas()
	{
		return $this->belongsToMany(Agenda::class, 'agenda_procedimentos', 'id')
					->withPivot('procedimento_id', 'qtde', 'valor', 'usuario_id')
					->withTimestamps();
	}

	public function procedimento_convenios()
	{
		// return $this->hasMany(ProcedimentoConvenio::class, 'procedimento_id');
        return $this->belongsToMany(
            ProcedimentoConvenio::class,
            'procedimento_convenios',
            'procedimento_id',
            'convenio_id'
        )->withPivot('usuario_id', 'created_at', 'updated_at');
	}


	public function procedimentos()
	{
		return $this->hasMany(Procedimento::class, 'procedimento_tipo_id');
	}
}

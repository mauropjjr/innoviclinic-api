<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\AutoSetEmpresaIdProfissionalIdUsuarioId;

/**
 * Class Seco
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
 * @property string $nome
 * @property string|null $formulario
 * @property int $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 * @property Collection|InteracaoAtendimento[] $interacao_atendimentos
 *
 * @package App\Models
 */
class Secao extends Model
{
    use AutoSetEmpresaIdProfissionalIdUsuarioId;

	protected $table = 'secoes';

	protected $casts = [
		'empresa_id' => 'int',
		'profissional_id' => 'int',
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
		'profissional_id',
		'nome',
		'formulario',
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

	public function interacao_atendimentos()
	{
		return $this->hasMany(InteracaoAtendimento::class, 'secao_id');
	}
}

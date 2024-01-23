<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use App\Traits\AutoSetUsuarioId;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmpresaProfissionai
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
 * @property bool $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class EmpresaProfissional extends Model
{
    use AutoSetUsuarioId;

	protected $table = 'empresa_profissionais';

	protected $casts = [
		'empresa_id' => 'int',
		'profissional_id' => 'int',
		'ativo' => 'bool',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'empresa_id',
		'profissional_id',
		'ativo',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->hasOne(Empresa::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}
}

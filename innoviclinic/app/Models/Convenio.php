<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Convenio
 * 
 * @property int $id
 * @property int $empresa_id
 * @property string $nome
 * @property string $numero_registro
 * @property string $tipo
 * @property int|null $dias_retorno
 * @property string|null $registro_ans
 * @property int|null $dias_recebimento
 * @property string|null $nome_banco_rec
 * @property int $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Empresa $empresa
 * @property Collection|Agenda[] $agendas
 * @property Collection|Prontuario[] $prontuarios
 *
 * @package App\Models
 */
class Convenio extends Model
{
	use HasFactory;
	protected $table = 'convenios';

	protected $casts = [
		'empresa_id' => 'int',
		'dias_retorno' => 'int',
		'dias_recebimento' => 'int',
		'ativo' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'empresa_id',
		'nome',
		'numero_registro',
		'tipo',
		'dias_retorno',
		'registro_ans',
		'dias_recebimento',
		'nome_banco_rec',
		'ativo',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function agendas()
	{
		return $this->hasMany(Agenda::class);
	}

	public function prontuarios()
	{
		return $this->hasMany(Prontuario::class);
	}
}

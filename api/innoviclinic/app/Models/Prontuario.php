<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Prontuario
 * 
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
 * @property int $paciente_id
 * @property int|null $convenio_id
 * @property string|null $atec_clinicos
 * @property string|null $atec_cirurgicos
 * @property string|null $antec_familiares
 * @property string|null $habitos
 * @property string|null $alergias
 * @property string|null $medicamentos
 * @property int|null $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Convenio|null $convenio
 * @property Empresa $empresa
 * @property Pessoa $pessoa
 * @property Collection|Interaco[] $interacos
 *
 * @package App\Models
 */
class Prontuario extends Model
{
	protected $table = 'prontuarios';

	protected $casts = [
		'empresa_id' => 'int',
		'profissional_id' => 'int',
		'paciente_id' => 'int',
		'convenio_id' => 'int',
		'ativo' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'empresa_id',
		'profissional_id',
		'paciente_id',
		'convenio_id',
		'atec_clinicos',
		'atec_cirurgicos',
		'antec_familiares',
		'habitos',
		'alergias',
		'medicamentos',
		'ativo',
		'usuario_id'
	];

	public function convenio()
	{
		return $this->belongsTo(Convenio::class);
	}

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}

	public function interacos()
	{
		return $this->hasMany(Interacao::class);
	}
}

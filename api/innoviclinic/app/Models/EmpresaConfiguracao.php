<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmpresaConfiguraco
 * 
 * @property int $id
 * @property int $empresa_id
 * @property string|null $hora_ini_agenda
 * @property string|null $hora_fim_agenda
 * @property string|null $duracao_atendimento
 * @property string|null $visualizacao_agenda
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Empresa $empresa
 *
 * @package App\Models
 */
class EmpresaConfiguracao extends Model
{
	use HasFactory;
	
	protected $table = 'empresa_configuracoes';

	protected $casts = [
		'empresa_id' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'empresa_id',
		'hora_ini_agenda',
		'hora_fim_agenda',
		'duracao_atendimento',
		'visualizacao_agenda',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}
}

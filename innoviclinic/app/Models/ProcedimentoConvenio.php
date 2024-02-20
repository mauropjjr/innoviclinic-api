<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcedimentoConvenio
 *
 * @property int $id
 * @property int $procedimento_id
 * @property int $convenio_id
 * @property float $valor
 * @property bool $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Procedimento $procedimento
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class ProcedimentoConvenio extends Model
{
	protected $table = 'procedimento_convenios';

	protected $casts = [
		'procedimento_id' => 'int',
        'convenio_id' => 'int',
		'valor' => 'float',
		'ativo' => 'bool',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'procedimento_id',
        'convenio_id',
		'valor',
		'ativo',
		'usuario_id'
	];

	public function procedimento()
	{
		return $this->belongsTo(Procedimento::class);
	}

    public function convenio()
	{
		return $this->belongsTo(Convenio::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}
}

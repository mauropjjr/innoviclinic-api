<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use App\Traits\AutoSetUsuarioId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AgendaProcedimento
 *
 * @property int $id
 * @property int $agenda_id
 * @property int $procedimento_id
 * @property int $qtde
 * @property float $valor
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Agenda $agenda
 * @property Procedimento $procedimento
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class AgendaProcedimento extends Model
{
    use AutoSetUsuarioId;
	use HasFactory;
	protected $table = 'agenda_procedimentos';

	protected $casts = [
		'agenda_id' => 'int',
		'procedimento_id' => 'int',
		'qtde' => 'int',
		'valor' => 'float',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
    ];

	protected $fillable = [
		'agenda_id',
		'procedimento_id',
		'qtde',
		'valor',
		'usuario_id'
	];

	public function agenda()
	{
		return $this->belongsTo(Agenda::class);
	}

	public function procedimento()
	{
		return $this->belongsTo(Procedimento::class, 'id');
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}
}

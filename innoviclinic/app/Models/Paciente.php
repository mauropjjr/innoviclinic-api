<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\AutoSetUsuarioId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Paciente
 *
 * @property int $pessoa_id
 * @property int|null $escolaridade_id
 * @property string|null $naturalidade
 * @property string|null $estado_civil
 * @property string|null $profissao
 * @property string|null $nome_mae
 * @property string|null $nome_pai
 * @property string|null $nome_responsavel
 * @property string|null $contato_emergencia
 * @property string|null $telefone_emergencia
 * @property string|null $tipo_sangue
 * @property int $obito
 * @property string|null $causa_mortis
 * @property int|null $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Escolaridade|null $escolaridade
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class Paciente extends Model
{
    use AutoSetUsuarioId;

	protected $table = 'pacientes';
	protected $primaryKey = 'pessoa_id';
	public $incrementing = false;

	protected $casts = [
		'pessoa_id' => 'int',
		'escolaridade_id' => 'int',
		'obito' => 'int',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
    ];

	protected $fillable = [
		'escolaridade_id',
		'naturalidade',
		'estado_civil',
		'profissao',
		'nome_mae',
		'nome_pai',
		'nome_responsavel',
		'contato_emergencia',
		'telefone_emergencia',
		'tipo_sangue',
		'obito',
		'causa_mortis',
		'usuario_id'
	];

	public function escolaridade()
	{
		return $this->belongsTo(Escolaridade::class);
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class);
	}
	
	public function agendas()
	{
		return $this->hasMany(Agenda::class, 'paciente_id', 'pessoa_id');
	}
}

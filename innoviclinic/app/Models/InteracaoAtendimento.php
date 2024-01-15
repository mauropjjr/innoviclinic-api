<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InteracaoAtendimento
 * 
 * @property int $id
 * @property int $interacao_id
 * @property int $secao_id
 * @property string $descricao
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Interaco $interaco
 * @property Seco $seco
 * @property Pessoa $pessoa
 *
 * @package App\Models
 */
class InteracaoAtendimento extends Model
{
	protected $table = 'interacao_atendimentos';

	protected $casts = [
		'interacao_id' => 'int',
		'secao_id' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'interacao_id',
		'secao_id',
		'descricao',
		'usuario_id'
	];

	public function interaco()
	{
		return $this->belongsTo(Interacao::class, 'interacao_id');
	}

	public function seco()
	{
		return $this->belongsTo(Secao::class, 'secao_id');
	}

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class, 'usuario_id');
	}
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Profissionai
 * 
 * @property int $pessoa_id
 * @property int $profissao_id
 * @property int $agenda_online
 * @property string|null $tratamento
 * @property string $nome_conselho
 * @property string $numero_conselho
 * @property string|null $rqe
 * @property string|null $cnes
 * @property string|null $uf_conselho
 * @property string|null $link_site
 * @property string|null $link_facebook
 * @property string|null $link_youtube
 * @property string|null $link_instagram
 * @property int|null $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Pessoa $pessoa
 * @property Profissao $profisso
 *
 * @package App\Models
 */
class Profissional extends Model
{
	protected $table = 'profissionais';
	protected $primaryKey = 'pessoa_id';
	public $incrementing = false;

	protected $casts = [
		'pessoa_id' => 'int',
		'profissao_id' => 'int',
		'agenda_online' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'profissao_id',
		'agenda_online',
		'tratamento',
		'nome_conselho',
		'numero_conselho',
		'rqe',
		'cnes',
		'uf_conselho',
		'link_site',
		'link_facebook',
		'link_youtube',
		'link_instagram',
		'usuario_id'
	];

	public function pessoa()
	{
		return $this->belongsTo(Pessoa::class);
	}

	public function profisso()
	{
		return $this->belongsTo(Profissao::class, 'profissao_id');
	}
}

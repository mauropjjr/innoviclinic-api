<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Testis
 * 
 * @property int $id
 * @property string $nome
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Teste extends Model
{
	use HasFactory;
	protected $table = 'testes';

	protected $fillable = [
		'nome'
	];
}

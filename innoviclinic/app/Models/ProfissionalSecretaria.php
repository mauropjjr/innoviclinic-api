<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\AutoSetEmpresaIdProfissionalIdUsuarioId;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProfissionalSecretaria
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
 * @property int $secretaria_id
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
class ProfissionalSecretaria extends Model
{
    use AutoSetEmpresaIdProfissionalIdUsuarioId;

	protected $table = 'profissional_secretarias';

	protected $casts = [
		'empresa_id' => 'int',
		'profissional_id' => 'int',
		'secretaria_id' => 'int',
		'ativo' => 'bool',
		'usuario_id' => 'int'
	];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
    ];

	protected $fillable = [
		'empresa_id',
		'profissional_id',
		'secretaria_id',
		'ativo',
		'usuario_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

    public function secretaria()
	{
		return $this->belongsTo(Pessoa::class, 'secretaria_id');
	}

	public function pessoa_profissional()
	{
		return $this->belongsTo(Pessoa::class, 'profissional_id');
	}

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->validateUniqueProfissionalSecretaria();
        });

        static::updating(function ($model) {
            $model->validateUniqueProfissionalSecretaria();
        });
    }

    private function validateUniqueProfissionalSecretaria()
    {
        $validator = Validator::make(
            [
                'empresa_id' => $this->empresa_id,
                'profissional_id' => $this->profissional_id,
                'secretaria_id' => $this->secretaria_id,
            ],
            [
                'secretaria_id' => Rule::unique('profissional_secretarias')
                    ->where(function ($query) {
                        $query->where('empresa_id', $this->empresa_id)
                            ->where('secretaria_id', $this->secretaria_id)
                            ->where('profissional_id', $this->profissional_id);
                    })
                    ->ignore($this->id),
            ]
        );

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\AutoSetEmpresaIdUsuarioId;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Class EmpresaProfissionai
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $profissional_id
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
class EmpresaProfissional extends Model
{
    use AutoSetEmpresaIdUsuarioId;

    protected $table = 'empresa_profissionais';

    protected $casts = [
        'empresa_id' => 'int',
        'profissional_id' => 'int',
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
        'ativo',
        'usuario_id'
    ];

    public function empresa()
    {
        return $this->hasOne(Empresa::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Pessoa::class, 'profissional_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->validateUniqueEmpresaProfissional();
        });

        static::updating(function ($model) {
            $model->validateUniqueEmpresaProfissional();
        });
    }

    private function validateUniqueEmpresaProfissional()
    {
        $validator = Validator::make(
            [
                'empresa_id' => $this->empresa_id,
                'profissional_id' => $this->profissional_id,
            ],
            [
                'profissional_id' => Rule::unique('empresa_profissionais')
                    ->where(function ($query) {
                        $query->where('empresa_id', $this->empresa_id)
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

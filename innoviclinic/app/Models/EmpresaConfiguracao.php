<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoSetEmpresaIdUsuarioId;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    use AutoSetEmpresaIdUsuarioId;

    protected $table = 'empresa_configuracoes';

    protected $casts = [
        'empresa_id' => 'int',
        'usuario_id' => 'int'
    ];

    protected $hidden = [
        'usuario_id',
        'created_at',
        'updated_at',
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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->validateUniqueEmpresaId();
        });

        static::updating(function ($model) {
            $model->validateUniqueEmpresaId();
        });
    }

    private function validateUniqueEmpresaId()
    {
        $validator = Validator::make(
            ['empresa_id' => $this->empresa_id],
            ['empresa_id' => Rule::unique('empresa_configuracoes', 'empresa_id')->ignore($this->id)]
        );

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\Utils;
use App\Traits\AutoSetUsuarioId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Empresa
 *
 * @property int $id
 * @property string $tipo
 * @property string $nome
 * @property string $email
 * @property string $telefone
 * @property string|null $razao_social
 * @property string|null $cpf_cnpj
 * @property string|null $inscricao_municipal
 * @property string|null $cnes
 * @property string|null $cep
 * @property string|null $logradouro
 * @property string|null $complemento
 * @property string|null $bairro
 * @property string|null $uf
 * @property string|null $cidade
 * @property string|null $foto
 * @property bool $ativo
 * @property int $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection|Agenda[] $agendas
 * @property Collection|Convenio[] $convenios
 * @property Collection|EmpresaConfiguraco[] $empresa_configuracos
 * @property Collection|EmpresaProfissionai[] $empresa_profissionais
 * @property Collection|Evento[] $eventos
 * @property Collection|Feriado[] $feriados
 * @property Collection|Procedimento[] $procedimentos
 * @property Collection|ProfissionalSecretaria[] $profissional_secretarias
 * @property Collection|Prontuario[] $prontuarios
 * @property Collection|Sala[] $salas
 * @property Collection|Seco[] $secos
 *
 * @package App\Models
 */
class Empresa extends Model
{
    use AutoSetUsuarioId;
	use HasFactory;
	protected $table = 'empresas';

	protected $casts = [
		'ativo' => 'bool',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'tipo',
		'nome',
		'email',
		'telefone',
		'razao_social',
		'cpf_cnpj',
		'inscricao_municipal',
		'cnes',
		'cep',
		'logradouro',
		'complemento',
		'bairro',
		'uf',
		'cidade',
		'foto',
		'ativo',
		'usuario_id'
	];

	public function agendas()
	{
		return $this->hasMany(Agenda::class);
	}

	public function convenios()
	{
		return $this->hasMany(Convenio::class);
	}

	public function empresa_configuracos()
	{
		return $this->hasMany(EmpresaConfiguracao::class);
	}

	public function empresa_profissionais()
	{
		return $this->hasMany(EmpresaProfissional::class);
	}

	public function eventos()
	{
		return $this->hasMany(Evento::class);
	}

	public function feriados()
	{
		return $this->hasMany(Feriado::class);
	}

	public function procedimentos()
	{
		return $this->hasMany(Procedimento::class);
	}

	public function profissional_secretarias()
	{
		return $this->hasMany(ProfissionalSecretaria::class);
	}

	public function prontuarios()
	{
		return $this->hasMany(Prontuario::class);
	}

	public function salas()
	{
		return $this->hasMany(Sala::class);
	}

	public function secos()
	{
		return $this->hasMany(Secao::class);
	}

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($empresa) {
            $empresa->cpf_cnpj = $empresa->tipo == 'PF' ? Utils::addMaskCpf($empresa->cpf_cnpj) : Utils::addMaskCnpj($empresa->cpf_cnpj);
        });
    }
}

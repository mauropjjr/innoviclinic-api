<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Pessoa
 *
 * @property int $id
 * @property string $tipo_usuario
 * @property string $nome
 * @property string|null $sexo
 * @property string|null $genero
 * @property string|null $email
 * @property string|null $celular
 * @property string|null $telefone
 * @property string|null $senha
 * @property Carbon|null $data_nascimento
 * @property string|null $cpf
 * @property string|null $rg
 * @property string|null $cep
 * @property string|null $logradouro
 * @property string|null $complemento
 * @property string|null $bairro
 * @property string|null $uf
 * @property string|null $cidade
 * @property string|null $observacoes
 * @property string|null $foto
 * @property int $admin
 * @property bool $ativo
 * @property int|null $usuario_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Pessoa|null $pessoa
 * @property Collection|AgendaProcedimento[] $agenda_procedimentos
 * @property Collection|AgendaStatus[] $agenda_statuses
 * @property Collection|Agenda[] $agendas
 * @property Collection|EmpresaProfissionai[] $empresa_profissionais
 * @property Collection|Escolaridade[] $escolaridades
 * @property Collection|Especialidade[] $especialidades
 * @property Collection|EventoProfissionai[] $evento_profissionais
 * @property Collection|Evento[] $eventos
 * @property Collection|InteracaoAtendimento[] $interacao_atendimentos
 * @property Collection|Interaco[] $interacos
 * @property Paciente $paciente
 * @property Collection|Pessoa[] $pessoas
 * @property Collection|ProcedimentoConvenio[] $procedimento_convenios
 * @property Collection|ProcedimentoTipo[] $procedimento_tipos
 * @property Collection|Procedimento[] $procedimentos
 * @property Profissionai $profissionai
 * @property Collection|ProfissionalEspecialidade[] $profissional_especialidades
 * @property Collection|ProfissionalSecretaria[] $profissional_secretarias
 * @property Collection|Profissao[] $profissoes
 * @property Collection|Prontuario[] $prontuarios
 * @property Collection|Sala[] $salas
 * @property Collection|Seco[] $secos
 *
 * @package App\Models
 */

class Pessoa extends  Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pessoas';

    protected $casts = [
        'data_nascimento' => 'datetime',
        'admin' => 'int',
        'ativo' => 'bool',
        'usuario_id' => 'int',
        'senha' => 'hashed',
    ];

    protected $fillable = [
        'tipo_usuario',
        'nome',
        'sexo',
        'genero',
        'email',
        'celular',
        'telefone',
        'senha',
        'data_nascimento',
        'cpf',
        'rg',
        'cep',
        'logradouro',
        'complemento',
        'bairro',
        'uf',
        'cidade',
        'observacoes',
        'foto',
        'admin',
        'ativo',
        'usuario_id'
    ];

    protected $hidden = [
        'senha',
        'remember_token',
        'usuario_id',
        'created_at',
        'updated_at',
    ];

    public function empresa_profissional()
    {
        return $this->hasOne(EmpresaProfissional::class, 'profissional_id');
    }

    public function profissional_secretaria()
    {
        return $this->hasOne(ProfissionalSecretaria::class, 'secretaria_id');
    }

    public function profissional()
    {
        return $this->hasOne(Profissional::class, 'pessoa_id');
    }

    public function profissional_especialidades()
    {
        return $this->belongsToMany(
            ProfissionalEspecialidade::class,
            'profissional_especialidades',
            'profissional_id',
            'especialidade_id'
        )->withPivot('usuario_id', 'created_at');
    }

    public function usuario()
    {
        return $this->belongsTo(Pessoa::class, 'usuario_id');
    }

    public function agenda_procedimentos()
    {
        return $this->hasMany(AgendaProcedimento::class, 'usuario_id');
    }

    public function agenda_statuses()
    {
        return $this->hasMany(AgendaStatus::class, 'usuario_id');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'profissional_id');
    }

    public function escolaridades()
    {
        return $this->hasMany(Escolaridade::class, 'usuario_id');
    }

    public function evento_profissionais()
    {
        return $this->hasMany(EventoProfissional::class, 'usuario_id');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'usuario_id');
    }

    public function interacao_atendimentos()
    {
        return $this->hasMany(InteracaoAtendimento::class, 'usuario_id');
    }

    public function interacos()
    {
        return $this->hasMany(Interacao::class, 'usuario_id');
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    public function pessoas()
    {
        return $this->hasMany(Pessoa::class, 'usuario_id');
    }

    public function procedimento_convenios()
    {
        return $this->hasMany(ProcedimentoConvenio::class, 'usuario_id');
    }

    public function procedimento_tipos()
    {
        return $this->hasMany(ProcedimentoTipo::class, 'usuario_id');
    }

    public function procedimentos()
    {
        return $this->hasMany(Procedimento::class, 'usuario_id');
    }

    public function profissional_secretarias()
    {
        return $this->hasMany(ProfissionalSecretaria::class, 'secretaria_id');
    }

    public function profissos()
    {
        return $this->hasMany(Profissao::class, 'usuario_id');
    }

    public function prontuarios()
    {
        return $this->hasMany(Prontuario::class, 'usuario_id');
    }

    public function salas()
    {
        return $this->hasMany(Sala::class, 'usuario_id');
    }

    public function secos()
    {
        return $this->hasMany(Secao::class, 'usuario_id');
    }
}

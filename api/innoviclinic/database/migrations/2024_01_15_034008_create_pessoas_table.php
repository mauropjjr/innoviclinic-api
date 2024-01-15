<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->char('tipo_usuario', 12)->comment('Profissional, Secretaria, Paciente');
            $table->string('nome');
            $table->char('sexo', 1)->nullable()->comment('M: Masculino, F: Feminino');
            $table->string('genero', 20)->nullable()->comment('Cisgênero, Transgênero, Não binário, Agênero');
            $table->string('email', 100)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('senha')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('rg', 25)->nullable();
            $table->string('cep', 9)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro', 150)->nullable();
            $table->char('uf', 2)->nullable();
            $table->string('cidade', 150)->nullable();
            $table->string('observacoes', 1000)->nullable();
            $table->string('foto')->nullable();
            $table->tinyInteger('admin')->default(0)->comment('Administrador da conta');
            $table->boolean('ativo')->default(true);
            $table->integer('usuario_id')->nullable()->index('pessoas_usuarios_FK');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoas');
    }
};

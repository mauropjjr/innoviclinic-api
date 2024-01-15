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
        Schema::create('empresas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->char('tipo', 2)->comment('PF, PJ');
            $table->string('nome');
            $table->string('email', 100);
            $table->string('telefone', 20);
            $table->string('razao_social')->nullable();
            $table->string('cpf_cnpj', 18)->nullable();
            $table->string('inscricao_municipal', 30)->nullable();
            $table->string('cnes', 20)->nullable();
            $table->string('cep', 9)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro', 150)->nullable();
            $table->char('uf', 2)->nullable();
            $table->string('cidade', 150)->nullable();
            $table->string('foto')->nullable();
            $table->boolean('ativo')->default(true);
            $table->integer('usuario_id');
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
        Schema::dropIfExists('empresas');
    }
};

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
        Schema::create('convenios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('convenios_empresas_FK');
            $table->string('nome');
            $table->string('numero_registro')->nullable();
            $table->string('tipo', 20)->default('Particular')->comment('Convênio, Particular');
            $table->integer('dias_retorno')->nullable();
            $table->string('registro_ans', 100)->nullable();
            $table->integer('dias_recebimento')->nullable();
            $table->string('nome_banco_rec', 100)->nullable();
            $table->tinyInteger('ativo')->default(1);
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
        Schema::dropIfExists('convenios');
    }
};

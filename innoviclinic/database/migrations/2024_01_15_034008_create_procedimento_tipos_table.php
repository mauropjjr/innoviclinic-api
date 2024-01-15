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
        Schema::create('procedimento_tipos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nome')->comment('Consulta, Retorno, Exame, Encaixe, Procedimento');
            $table->tinyInteger('ativo')->default(1);
            $table->integer('usuario_id')->index('procedimentos_usuarios_FK');
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
        Schema::dropIfExists('procedimento_tipos');
    }
};

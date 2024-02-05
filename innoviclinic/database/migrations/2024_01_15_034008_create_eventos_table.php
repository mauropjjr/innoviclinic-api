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
        Schema::create('eventos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('eventos_empresas_FK');
            $table->string('nome');
            $table->date('data_ini');
            $table->date('data_fim');
            $table->string('descricao')->nullable();
            $table->string('hora_ini', 5);
            $table->string('hora_fim', 5);
            $table->string('dias_semana', 20)->nullable()->comment('Ex. 1,2,4,5,6,0');
            $table->string('cor', 50);
            $table->integer('usuario_id')->index('eventos_usuarios_FK');
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
        Schema::dropIfExists('eventos');
    }
};

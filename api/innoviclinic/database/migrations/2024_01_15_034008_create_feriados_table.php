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
        Schema::create('feriados', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->nullable()->index('feriados_empresas_FK');
            $table->date('data');
            $table->string('nome', 100);
            $table->string('descricao', 100);
            $table->string('tipo', 50)->comment('Feriado Nacional, Feriado Estadual, Feriado Municipal, Dia Convencional');
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
        Schema::dropIfExists('feriados');
    }
};

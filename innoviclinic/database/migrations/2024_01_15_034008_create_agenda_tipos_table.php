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
        Schema::create('agenda_tipos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('agenda_tipos_empresas_FK');
            $table->string('nome', 100);
            $table->string('cor', 50);
            $table->tinyInteger('ativo')->default(1);
            $table->tinyInteger('sem_horario')->default(1);
            $table->tinyInteger('sem_procedimento')->default(1);
            $table->integer('usuario_id')->index('agenda_tipos_usuarios_FK');
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
        Schema::dropIfExists('agenda_tipos');
    }
};

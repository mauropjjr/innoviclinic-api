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
        Schema::create('interacoes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('prontuario_id')->index('interacoes_prontuarios_FK');
            $table->integer('agenda_id')->nullable()->index('interacoes_agendas_FK');
            $table->date('data');
            $table->string('hora_ini', 5);
            $table->string('hora_fim', 5)->nullable();
            $table->string('tempo_atendimento', 5)->nullable();
            $table->tinyInteger('finalizado')->default(0);
            $table->tinyInteger('teleatendimento')->default(0);
            $table->integer('usuario_id')->index('interacoes_usuarios_FK');
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
        Schema::dropIfExists('interacoes');
    }
};

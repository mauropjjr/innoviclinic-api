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
        Schema::create('empresa_configuracoes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('empresa_configuracoes_empresas_FK');
            $table->string('hora_ini_agenda', 5)->nullable();
            $table->string('hora_fim_agenda', 5)->nullable();
            $table->string('duracao_atendimento', 5)->nullable();
            $table->string('visualizacao_agenda', 100)->nullable();
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
        Schema::dropIfExists('empresa_configuracoes');
    }
};

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
        Schema::create('interacao_atendimentos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('interacao_id')->index('interacao_atendimentos_interacoes_FK');
            $table->integer('secao_id')->index('interacao_atendimentos_secoes_FK');
            $table->mediumText('descricao');
            $table->integer('usuario_id')->index('interacao_atendimentos_usuarios_FK');
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
        Schema::dropIfExists('interacao_atendimentos');
    }
};

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
        Schema::table('interacao_atendimentos', function (Blueprint $table) {
            $table->foreign(['interacao_id'], 'interacao_atendimentos_interacoes_FK')->references(['id'])->on('interacoes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['secao_id'], 'interacao_atendimentos_secoes_FK')->references(['id'])->on('secoes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'interacao_atendimentos_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interacao_atendimentos', function (Blueprint $table) {
            $table->dropForeign('interacao_atendimentos_interacoes_FK');
            $table->dropForeign('interacao_atendimentos_secoes_FK');
            $table->dropForeign('interacao_atendimentos_usuarios_FK');
        });
    }
};

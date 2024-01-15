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
        Schema::table('interacoes', function (Blueprint $table) {
            $table->foreign(['agenda_id'], 'interacoes_agendas_FK')->references(['id'])->on('agendas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['prontuario_id'], 'interacoes_prontuarios_FK')->references(['id'])->on('prontuarios')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'interacoes_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interacoes', function (Blueprint $table) {
            $table->dropForeign('interacoes_agendas_FK');
            $table->dropForeign('interacoes_prontuarios_FK');
            $table->dropForeign('interacoes_usuarios_FK');
        });
    }
};

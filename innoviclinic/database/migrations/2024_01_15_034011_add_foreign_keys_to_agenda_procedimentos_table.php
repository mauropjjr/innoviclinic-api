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
        Schema::table('agenda_procedimentos', function (Blueprint $table) {
            $table->foreign(['agenda_id'], 'agenda_procedimentos_agendas_FK')->references(['id'])->on('agendas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id'], 'agenda_procedimentos_procedimentos_FK')->references(['id'])->on('procedimentos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'agenda_procedimentos_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda_procedimentos', function (Blueprint $table) {
            $table->dropForeign('agenda_procedimentos_agendas_FK');
            $table->dropForeign('agenda_procedimentos_procedimentos_FK');
            $table->dropForeign('agenda_procedimentos_usuarios_FK');
        });
    }
};

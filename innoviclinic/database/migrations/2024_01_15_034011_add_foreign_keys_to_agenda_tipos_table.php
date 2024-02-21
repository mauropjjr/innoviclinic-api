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
        Schema::table('agenda_tipos', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'agenda_tipos_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'agenda_tipos_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
            $table->dropForeign('agenda_tipos_empresas_FK');
            $table->dropForeign('agenda_tipos_usuarios_FK');
        });
    }
};

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
        Schema::table('pacientes', function (Blueprint $table) {
            $table->foreign(['escolaridade_id'], 'pacientes_escolaridades_FK')->references(['id'])->on('escolaridades')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['pessoa_id'], 'pacientes_pessoas_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['pessoa_id'], 'pacientes_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropForeign('pacientes_escolaridades_FK');
            $table->dropForeign('pacientes_pessoas_FK');
            $table->dropForeign('pacientes_usuarios_FK');
        });
    }
};

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
        Schema::table('profissional_especialidades', function (Blueprint $table) {
            $table->foreign(['especialidade_id'], 'profissional_especialidades_FK')->references(['id'])->on('especialidades')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['profissional_id'], 'profissional_especialidades_pessoas_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'profissional_especialidades_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profissional_especialidades', function (Blueprint $table) {
            $table->dropForeign('profissional_especialidades_FK');
            $table->dropForeign('profissional_especialidades_pessoas_FK');
            $table->dropForeign('profissional_especialidades_usuarios_FK');
        });
    }
};

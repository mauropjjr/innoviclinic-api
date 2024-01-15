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
        Schema::table('prontuarios', function (Blueprint $table) {
            $table->foreign(['convenio_id'], 'prontuarios_convenios_FK')->references(['id'])->on('convenios')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['empresa_id'], 'prontuarios_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['paciente_id'], 'prontuarios_pacientes_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['profissional_id'], 'prontuarios_profissionais_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'prontuarios_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prontuarios', function (Blueprint $table) {
            $table->dropForeign('prontuarios_convenios_FK');
            $table->dropForeign('prontuarios_empresas_FK');
            $table->dropForeign('prontuarios_pacientes_FK');
            $table->dropForeign('prontuarios_profissionais_FK');
            $table->dropForeign('prontuarios_usuarios_FK');
        });
    }
};

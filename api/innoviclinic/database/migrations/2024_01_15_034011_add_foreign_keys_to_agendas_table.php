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
        Schema::table('agendas', function (Blueprint $table) {
            $table->foreign(['agenda_status_id'], 'agendas_agenda_status_FK')->references(['id'])->on('agenda_status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['convenio_id'], 'agendas_convenios_FK')->references(['id'])->on('convenios')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['empresa_id'], 'agendas_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['paciente_id'], 'agendas_pacientes_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['profissional_id'], 'agendas_profissionais_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['sala_id'], 'agendas_salas_FK')->references(['id'])->on('salas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropForeign('agendas_agenda_status_FK');
            $table->dropForeign('agendas_convenios_FK');
            $table->dropForeign('agendas_empresas_FK');
            $table->dropForeign('agendas_pacientes_FK');
            $table->dropForeign('agendas_profissionais_FK');
            $table->dropForeign('agendas_salas_FK');
        });
    }
};

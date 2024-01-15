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
        Schema::create('agendas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('agendas_empresas_FK');
            $table->integer('profissional_id')->index('agendas_profissionais_FK');
            $table->integer('sala_id')->index('agendas_salas_FK');
            $table->integer('paciente_id')->index('agendas_pacientes_FK');
            $table->integer('convenio_id')->index('agendas_convenios_FK');
            $table->integer('agenda_status_id')->index('agendas_agenda_status_FK');
            $table->string('nome', 100);
            $table->string('celular', 20);
            $table->string('telefone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->tinyInteger('primeiro_atend')->default(0)->comment('Primeiro atendimento?');
            $table->tinyInteger('enviar_msg')->default(0);
            $table->date('data');
            $table->string('hora_ini', 5);
            $table->string('hora_fim', 100);
            $table->integer('duracao_min');
            $table->tinyInteger('telemedicina')->default(0);
            $table->decimal('valor', 10)->default(0);
            $table->string('hora_chegada', 5)->nullable();
            $table->string('hora_atendimento_ini', 5)->nullable();
            $table->string('hora_atendimento_fim', 5)->nullable();
            $table->string('observacoes')->nullable();
            $table->string('motivo_cancelamento')->nullable();
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
        Schema::dropIfExists('agendas');
    }
};

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
        Schema::create('prontuarios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('prontuarios_empresas_FK');
            $table->integer('profissional_id')->index('prontuarios_profissionais_FK');
            $table->integer('paciente_id')->index('prontuarios_pacientes_FK');
            $table->integer('convenio_id')->nullable()->index('prontuarios_convenios_FK');
            $table->string('atec_clinicos', 1000)->nullable();
            $table->string('atec_cirurgicos', 1000)->nullable();
            $table->string('antec_familiares', 1000)->nullable();
            $table->string('habitos', 1000)->nullable();
            $table->string('alergias', 1000)->nullable();
            $table->string('medicamentos', 1000)->nullable();
            $table->tinyInteger('ativo')->nullable()->default(1);
            $table->integer('usuario_id')->index('prontuarios_usuarios_FK');
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
        Schema::dropIfExists('prontuarios');
    }
};

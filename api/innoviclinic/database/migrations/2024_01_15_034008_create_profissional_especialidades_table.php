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
        Schema::create('profissional_especialidades', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('profissional_id')->index('profissional_especialidades_pessoas_FK')->comment('Pessoa do tipo Profissional');
            $table->integer('especialidade_id')->index('profissional_especialidades_FK');
            $table->integer('usuario_id')->index('profissional_especialidades_usuarios_FK');
            $table->dateTime('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profissional_especialidades');
    }
};

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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->integer('pessoa_id')->primary();
            $table->integer('escolaridade_id')->nullable()->index('pacientes_escolaridades_FK');
            $table->string('naturalidade', 50)->nullable();
            $table->string('estado_civil', 50)->nullable();
            $table->string('profissao', 100)->nullable();
            $table->string('nome_mae', 100)->nullable();
            $table->string('nome_pai', 100)->nullable();
            $table->string('nome_responsavel', 100)->nullable();
            $table->string('contato_emergencia', 100)->nullable();
            $table->string('telefone_emergencia', 20)->nullable();
            $table->string('tipo_sangue', 5)->nullable();
            $table->tinyInteger('obito')->default(0);
            $table->string('causa_mortis', 100)->nullable();
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
        Schema::dropIfExists('pacientes');
    }
};

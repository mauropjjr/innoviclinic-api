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
        Schema::create('procedimento_tipos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('procedimento_tipos_empresas_FK');
            $table->string('nome')->comment('Cirurgia, Emergência, Exame Clínico, Prevenção, Prótese, Radiologia');
            $table->tinyInteger('ativo')->default(1);
            $table->integer('usuario_id')->index('procedimento_tipos_usuarios_FK');
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
        Schema::dropIfExists('procedimento_tipos');
    }
};

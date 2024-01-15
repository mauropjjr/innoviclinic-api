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
        Schema::create('secoes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('secoes_empresas_FK');
            $table->integer('profissional_id')->index('secoes_profissionais_FK');
            $table->string('nome', 100);
            $table->string('formulario', 3000)->nullable();
            $table->tinyInteger('ativo')->default(1);
            $table->integer('usuario_id')->index('secoes_usuarios_FK');
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
        Schema::dropIfExists('secoes');
    }
};

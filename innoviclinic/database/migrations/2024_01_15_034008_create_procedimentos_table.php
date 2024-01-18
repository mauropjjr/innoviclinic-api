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
        Schema::create('procedimentos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('procedimentos_empresas_FK');
            $table->integer('procedimento_tipo_id')->index('procedimentos_tipos_FK');
            $table->string('nome');
            $table->string('cor', 100);
            $table->integer('duracao_min');
            $table->decimal('valor', 10)->default(0);
            $table->tinyInteger('ativo')->default(1);
            $table->integer('usuario_id')->index('procedimentos_pessoa_usuarios_FK');
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
        Schema::dropIfExists('procedimentos');
    }
};

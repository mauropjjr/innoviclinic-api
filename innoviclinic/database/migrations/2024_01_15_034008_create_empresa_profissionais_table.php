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
        Schema::create('empresa_profissionais', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('empresa_profissionais_empresas_FK');
            $table->integer('profissional_id')->index('empresa_profissionais_pessoas_FK');
            $table->boolean('ativo')->default(true);
            $table->integer('usuario_id')->index('empresa_profissionais_usuarios_FK');
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
        Schema::dropIfExists('empresa_profissionais');
    }
};

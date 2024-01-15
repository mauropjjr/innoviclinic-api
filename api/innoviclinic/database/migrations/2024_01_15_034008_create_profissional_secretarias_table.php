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
        Schema::create('profissional_secretarias', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('profissional_secretarias_empresas_FK');
            $table->integer('profissional_id')->index('profissional_secretarias_profissionais_FK');
            $table->integer('secretaria_id')->index('secretaria_profissionais_secretarias_FK');
            $table->boolean('ativo')->default(true);
            $table->integer('usuario_id')->index('profissional_secretarias_usuarios_FK');
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
        Schema::dropIfExists('profissional_secretarias');
    }
};

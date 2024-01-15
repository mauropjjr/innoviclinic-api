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
        Schema::create('evento_profissionais', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('evento_id')->index('evento_profissionais_eventos_FK');
            $table->integer('profissional_id')->index('evento_profissionais_pessoas_FK');
            $table->integer('usuario_id')->index('evento_profissionais_usuarios_FK');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evento_profissionais');
    }
};

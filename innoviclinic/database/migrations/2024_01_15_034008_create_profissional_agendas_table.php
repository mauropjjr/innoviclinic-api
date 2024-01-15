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
        Schema::create('profissional_agendas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id');
            $table->integer('profissional_id');
            $table->integer('dia');
            $table->string('hora_ini', 5);
            $table->string('hora_fim', 5);
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
        Schema::dropIfExists('profissional_agendas');
    }
};

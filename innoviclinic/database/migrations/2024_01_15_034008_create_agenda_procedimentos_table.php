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
        Schema::create('agenda_procedimentos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('agenda_id')->index('agenda_procedimentos_agendas_FK');
            $table->integer('procedimento_id');
            $table->integer('qtde')->default(0);
            $table->decimal('valor', 10)->default(0);
            $table->integer('usuario_id')->index('agenda_procedimentos_usuarios_FK');
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
        Schema::dropIfExists('agenda_procedimentos');
    }
};

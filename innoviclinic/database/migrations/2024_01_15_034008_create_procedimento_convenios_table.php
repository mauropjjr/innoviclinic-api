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
        Schema::create('procedimento_convenios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('procedimento_id')->index('procedimento_convenios_procedimentos_FK');
            $table->integer('convenio_id')->index('procedimento_convenios_convenios_FK');
            $table->decimal('valor', 10)->default(0);
            $table->boolean('ativo')->default(true);
            $table->integer('usuario_id')->index('procedimento_convenios_usuarios_FK');
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
        Schema::dropIfExists('procedimento_convenios');
    }
};

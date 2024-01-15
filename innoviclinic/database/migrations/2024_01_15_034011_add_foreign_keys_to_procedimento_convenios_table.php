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
        Schema::table('procedimento_convenios', function (Blueprint $table) {
            $table->foreign(['procedimento_id'], 'procedimento_convenios_procedimentos_FK')->references(['id'])->on('procedimentos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'procedimento_convenios_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimento_convenios', function (Blueprint $table) {
            $table->dropForeign('procedimento_convenios_procedimentos_FK');
            $table->dropForeign('procedimento_convenios_usuarios_FK');
        });
    }
};

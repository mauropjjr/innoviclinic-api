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
        Schema::table('feriados', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'feriados_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feriados', function (Blueprint $table) {
            $table->dropForeign('feriados_empresas_FK');
        });
    }
};

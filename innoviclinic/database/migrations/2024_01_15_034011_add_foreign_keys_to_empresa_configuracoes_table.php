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
        Schema::table('empresa_configuracoes', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'empresa_configuracoes_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresa_configuracoes', function (Blueprint $table) {
            $table->dropForeign('empresa_configuracoes_empresas_FK');
        });
    }
};

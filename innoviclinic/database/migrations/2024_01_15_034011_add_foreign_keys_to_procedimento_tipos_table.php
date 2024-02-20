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
        Schema::table('procedimento_tipos', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'procedimento_tipos_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'procedimento_tipos_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimento_tipos', function (Blueprint $table) {
            $table->dropForeign('procedimento_tipos_empresas_FK');
            $table->dropForeign('procedimento_tipos_usuarios_FK');
        });
    }
};

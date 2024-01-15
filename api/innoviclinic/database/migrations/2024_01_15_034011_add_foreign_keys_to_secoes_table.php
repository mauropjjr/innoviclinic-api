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
        Schema::table('secoes', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'secoes_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['profissional_id'], 'secoes_profissionais_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'secoes_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('secoes', function (Blueprint $table) {
            $table->dropForeign('secoes_empresas_FK');
            $table->dropForeign('secoes_profissionais_FK');
            $table->dropForeign('secoes_usuarios_FK');
        });
    }
};

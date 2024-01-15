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
        Schema::table('empresa_profissionais', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'empresa_profissionais_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['profissional_id'], 'empresa_profissionais_pessoas_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'empresa_profissionais_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresa_profissionais', function (Blueprint $table) {
            $table->dropForeign('empresa_profissionais_empresas_FK');
            $table->dropForeign('empresa_profissionais_pessoas_FK');
            $table->dropForeign('empresa_profissionais_usuarios_FK');
        });
    }
};

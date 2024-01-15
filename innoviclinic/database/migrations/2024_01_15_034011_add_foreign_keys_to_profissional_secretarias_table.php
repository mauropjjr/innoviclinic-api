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
        Schema::table('profissional_secretarias', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'profissional_secretarias_empresas_FK')->references(['id'])->on('empresas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['profissional_id'], 'profissional_secretarias_profissionais_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'profissional_secretarias_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['secretaria_id'], 'secretaria_profissionais_secretarias_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profissional_secretarias', function (Blueprint $table) {
            $table->dropForeign('profissional_secretarias_empresas_FK');
            $table->dropForeign('profissional_secretarias_profissionais_FK');
            $table->dropForeign('profissional_secretarias_usuarios_FK');
            $table->dropForeign('secretaria_profissionais_secretarias_FK');
        });
    }
};

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
        Schema::table('evento_profissionais', function (Blueprint $table) {
            $table->foreign(['evento_id'], 'evento_profissionais_eventos_FK')->references(['id'])->on('eventos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['profissional_id'], 'evento_profissionais_pessoas_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['usuario_id'], 'evento_profissionais_usuarios_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evento_profissionais', function (Blueprint $table) {
            $table->dropForeign('evento_profissionais_eventos_FK');
            $table->dropForeign('evento_profissionais_pessoas_FK');
            $table->dropForeign('evento_profissionais_usuarios_FK');
        });
    }
};

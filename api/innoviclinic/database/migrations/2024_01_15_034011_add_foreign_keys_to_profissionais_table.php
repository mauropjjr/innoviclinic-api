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
        Schema::table('profissionais', function (Blueprint $table) {
            $table->foreign(['pessoa_id'], 'profissionais_pessoas_FK')->references(['id'])->on('pessoas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['profissao_id'], 'profissionais_profissoes_FK')->references(['id'])->on('profissoes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropForeign('profissionais_pessoas_FK');
            $table->dropForeign('profissionais_profissoes_FK');
        });
    }
};

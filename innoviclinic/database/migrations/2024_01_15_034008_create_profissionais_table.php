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
        Schema::create('profissionais', function (Blueprint $table) {
            $table->integer('pessoa_id')->primary();
            $table->integer('profissao_id')->index('profissionais_profissoes_FK');
            $table->tinyInteger('agenda_online')->default(0)->comment('Permite o paciente agendar online');
            $table->string('tratamento', 10)->nullable()->comment('Dra. - Dr. - Sra. - Sr.');
            $table->string('nome_conselho', 20);
            $table->string('numero_conselho', 20);
            $table->string('rqe', 20)->nullable();
            $table->string('cnes', 20)->nullable();
            $table->char('uf_conselho', 2)->nullable();
            $table->string('link_site', 100)->nullable();
            $table->string('link_facebook', 100)->nullable();
            $table->string('link_youtube', 100)->nullable();
            $table->string('link_instagram', 100)->nullable();
            $table->integer('usuario_id')->nullable();
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
        Schema::dropIfExists('profissionais');
    }
};

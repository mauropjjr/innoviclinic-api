<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plano_modulos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plano_id')->unsigned();
            $table->integer('modulo_id')->unsigned();
            $table->foreign('plano_id')->references('id')->on('planos')->onDelete('cascade');
            $table->foreign('modulo_id')->references('id')->on('modulos')->onDelete('cascade');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plano_modulos');
    }
};

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
        Schema::create('planos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('produto', 50)->default('InnoviClinic')->comment('InnoviClinic, InnoviChat, InnoviCrm');
            $table->string('nome');
            $table->string('descricao', 1000)->nullable();
            $table->decimal('valor', 10);
            $table->tinyInteger('ativo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};

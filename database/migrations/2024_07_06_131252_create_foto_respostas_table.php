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
        Schema::create('foto_respostas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resposta_id');
            $table->string('foto');
            $table->timestamps();


            $table->foreign('resposta_id')->references('id')->on('resposta_solicitacoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foto_respostas');
    }
};

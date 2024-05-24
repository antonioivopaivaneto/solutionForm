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
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('condominio_id');
            $table->unsignedBigInteger('unidade_id');
            $table->string('assunto');
            $table->string('solicitacao');
            $table->boolean('proprietario');
            $table->string('nome');
            $table->string('email');
            $table->timestamps();

            $table->foreign('condominio_id')->references('id')->on('condominios');
            $table->foreign('unidade_id')->references('id')->on('unidades');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacaos');
    }
};

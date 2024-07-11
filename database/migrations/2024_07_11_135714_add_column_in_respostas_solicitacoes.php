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
        Schema::table('resposta_solicitacoes', function (Blueprint $table) {
            $table->foreignId('solicitacao_id')->constrained('solicitacoes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resposta_solicitacoes', function (Blueprint $table) {
            $table->dropColumn('solicitacao_id');
        });
    }
};

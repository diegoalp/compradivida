<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propostas', function (Blueprint $table) {
            $table->id();
            $table->string('protocolo');
            $table->string('nome');
            $table->string('cpf');
            $table->string('telefone');
            $table->string('nome_vendedor');
            $table->string('banco_boleto');
            $table->float('valor_boleto')->nullable();
            $table->date('data_vencimento_boleto')->nullable();
            $table->date('data_quitacao_boleto')->nullable();
            $table->date('data_finalizacao_boleto')->nullable();
            $table->float('rendimento')->nullable();
            $table->float('comissao_total')->nullable();
            $table->float('comissao_escritorio')->nullable();
            $table->float('comissao_vendedor')->nullable();
            $table->integer('status');
            $table->string('agencia_quitacao');
            $table->string('banco_quitacao');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('propostas');
    }
}

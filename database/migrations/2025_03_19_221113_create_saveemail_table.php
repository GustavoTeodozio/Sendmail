<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaveemailTable extends Migration
{
    public function up()
    {
        Schema::create('saveemail', function (Blueprint $table) {
            // Criação do id como chave primária
            $table->id();

            // Definindo o user_id, que será uma chave estrangeira
            $table->unsignedBigInteger('user_id');

            // Campos para armazenar o email, assunto e mensagem
            $table->string('email');
            $table->string('subject');
            $table->text('message');

            // Campos de timestamps (criado e atualizado)
            $table->timestamps();

            // Chave estrangeira para o campo user_id, referenciando a tabela users (supondo que a tabela users exista)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('saveemail');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSorteiosTable extends Migration
{
    public function up()
    {
        Schema::create('sorteios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('quantidade_ganhadores');
            $table->string('premio_tipo');
            $table->integer('premio_quantidade');
            $table->text('premio_detalhes')->nullable(); // Armazena detalhes específicos como a quantidade de moedas/HC
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sorteios');
    }
}
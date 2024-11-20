<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSorteioPremiosTable extends Migration
{
    public function up()
    {
        Schema::create('sorteio_premios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sorteio_id')->constrained()->onDelete('cascade');
            $table->integer('posicao'); // posição de 1º, 2º lugar, etc.
            $table->string('premio_tipo'); // 'cambios' ou 'hc'
            $table->integer('premio_quantidade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sorteio_premios');
    }
}

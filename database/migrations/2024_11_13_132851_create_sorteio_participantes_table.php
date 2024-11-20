<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSorteioParticipantesTable extends Migration
{
    public function up()
    {
        Schema::create('sorteio_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sorteio_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sorteio_participantes');
    }
}
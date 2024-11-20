<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVencedorIdAndDataSorteioToSorteiosTable extends Migration
{
    public function up()
    {
        Schema::table('sorteios', function (Blueprint $table) {
            $table->unsignedBigInteger('vencedor_id')->nullable()->after('user_id'); // Coluna para armazenar o ID do vencedor
            $table->dateTime('data_sorteio')->nullable()->after('vencedor_id'); // Coluna para armazenar a data do sorteio

            $table->foreign('vencedor_id')->references('id')->on('users')->onDelete('set null'); // Define chave estrangeira
        });
    }

    public function down()
    {
        Schema::table('sorteios', function (Blueprint $table) {
            $table->dropForeign(['vencedor_id']);
            $table->dropColumn(['vencedor_id', 'data_sorteio']);
        });
    }
}

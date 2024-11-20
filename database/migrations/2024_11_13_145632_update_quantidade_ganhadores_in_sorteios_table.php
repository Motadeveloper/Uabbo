<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sorteios', function (Blueprint $table) {
            $table->integer('quantidade_ganhadores')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sorteios', function (Blueprint $table) {
            $table->integer('quantidade_ganhadores')->nullable(false)->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sorteios', function (Blueprint $table) {
            $table->dropColumn('premio_quantidade');
        });
    }

    public function down(): void
    {
        Schema::table('sorteios', function (Blueprint $table) {
            $table->integer('premio_quantidade')->nullable(); // Caso queira reverter a migração
        });
    }
};

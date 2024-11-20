<?php
// database/migrations/xxxx_xx_xx_create_forum_posts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumPostsTable extends Migration
{
    public function up()
    {
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'topic' ou 'promotion'
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->json('room_data')->nullable();
            $table->text('description')->nullable(); // Legenda do quarto promovido
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forum_posts');
    }
}

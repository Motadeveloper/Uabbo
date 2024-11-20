<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\CommentController;

// Rota da API para o Feed
Route::get('/feed', [FeedController::class, 'index'])->name('api.feed');

// Rotas para Tópicos e Comentários
Route::prefix('topics')->group(function () {
    // Criar um comentário principal para um tópico
    Route::post('/{topic}/replies', [CommentController::class, 'storeReply'])->name('comments.storeReply');

    // Carregar comentários principais de um tópico com suporte a limite
    Route::get('/{topic}/comments', [CommentController::class, 'getComments'])->name('comments.get');

    // Carregar todas as respostas de um tópico
    Route::get('/{topic}/replies', [CommentController::class, 'index'])->name('comments.index');
});

// Rotas para Respostas Aninhadas
Route::prefix('replies')->group(function () {
    // Criar uma resposta para um comentário
    Route::post('/{reply}/replies', [CommentController::class, 'storeNestedReply'])->name('comments.storeNestedReply');
});




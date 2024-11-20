<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HabboController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\SorteioController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Rota principal (redireciona para o feed)
Route::get('/', function () {
    return redirect('/feed');
});

// Autenticação
Auth::routes();
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
Route::post('/fetch-habbo-data', [ForgotPasswordController::class, 'fetchHabboData'])->name('fetch-habbo-data');
Route::post('/fetch-habbo-info', [ForgotPasswordController::class, 'fetchHabboInfo'])->name('fetch.habbo.info');

// Rotas do Feed e Home
Route::get('/feed', [HomeController::class, 'index'])->name('home');

// API para o feed (carregamento dinâmico de tópicos)
Route::get('/api/feed', [TopicController::class, 'apiIndex'])->name('api.feed');

// Rota de Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rota para informações do Habbo
Route::get('/habbo-info', [HabboController::class, 'showForm'])->name('habbo.showForm');
Route::post('/habbo-info', [HabboController::class, 'fetchHabboInfo'])->name('habbo.fetchInfo');

// Rotas do Fórum
Route::resource('topics', TopicController::class)->middleware('auth');
Route::delete('/topics/{id}', [TopicController::class, 'destroy'])->name('topics.destroy')->middleware('auth');

// API para Respostas Encadeadas
Route::prefix('api')->group(function () {
    // Respostas de um tópico
    Route::get('/topics/{topic}/replies', [CommentController::class, 'index'])->name('comments.index');

    Route::get('topics/{topic}/comments', [CommentController::class, 'getComments'])->name('comments.get');
    // Criar uma nova resposta
    Route::post('/topics/{topic}/replies', [CommentController::class, 'storeReply'])->name('comments.storeReply');
    // Resposta para outro comentário
    Route::post('/replies/{reply}/replies', [CommentController::class, 'storeNestedReply'])->name('comments.storeNestedReply');
});

Route::post('/api/replies/{reply}/replies', [CommentController::class, 'storeNestedReply'])->name('comments.storeNestedReply');


// Rota para Promover Quarto
Route::resource('forumPosts', ForumPostController::class)->middleware('auth');

// Rotas de Sorteios
Route::get('/sorteios', [SorteioController::class, 'index'])->name('sorteios.index');
Route::get('/sorteio/{id}', [SorteioController::class, 'show'])->name('sorteios.show');

Route::middleware('auth')->group(function () {
    Route::post('/sorteios/store', [SorteioController::class, 'store'])->name('sorteios.store');
    Route::post('/sorteio/{id}/participar', [SorteioController::class, 'participar'])->name('sorteios.participar');
    Route::post('/sorteio/{id}/gerar', [SorteioController::class, 'gerar'])->name('sorteios.gerar');
    Route::delete('/sorteio/{id}/cancelar', [SorteioController::class, 'cancelar'])->name('sorteios.cancelar');
});
Route::get('/sorteios/create/form', [SorteioController::class, 'createForm'])->name('sorteios.create.form');
Route::post('/sorteios/{sorteio}/comentarios', [ComentarioController::class, 'store'])->name('sorteios.comentarios');
Route::get('/api/sorteios', [SorteioController::class, 'getSorteios'])->name('sorteios.api');

<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Models\Community;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas web para a sua aplicação.
|
*/

// Rota de Boas-vindas
Route::get('/', function () {
    return view('welcome');
});

// Rota do Dashboard Principal
Route::get('/dashboard', function () {
    // Adicionado 'community' ao eager loading e paginação para melhor performance
    $posts = Post::with(['user', 'comments.user', 'likedByUsers', 'community'])->latest()->paginate(10);
    $popularCommunities = Community::withCount('members')->orderBy('members_count', 'desc')->take(5)->get();
    return view('dashboard', [
        'posts' => $posts,
        'popularCommunities' => $popularCommunities,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


// Grupo de rotas que exigem autenticação e verificação de email
Route::middleware(['auth', 'verified'])->group(function () {

    // --- PERFIL E SEGUIR USUÁRIOS ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/users/{user}/follow', [ProfileController::class, 'toggleFollow'])->name('profile.toggleFollow');

    // --- ADMIN: GERENCIAMENTO DE USUÁRIOS ---
    // Rotas para um admin criar e listar usuários
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

    // --- COMUNIDADES ---
    // Route::resource cria todas as rotas CRUD (index, create, store, show, edit, update, destroy)
    Route::resource('communities', CommunityController::class);
    Route::post('/communities/{community}/join', [CommunityController::class, 'toggleJoin'])->name('communities.toggleJoin');

    // --- POSTS ---
    // Você já tinha um resource, o que é ótimo. Mantivemos.
    Route::resource('posts', PostController::class)->except(['index']);
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.toggleLike');

    // --- COMENTÁRIOS ---
    // Rota para salvar um novo comentário em um post
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    // Rotas para atualizar e apagar um comentário existente
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // --- BUSCA ---
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/posts/{post}/toggle-pin', [PostController::class, 'togglePin'])->name('posts.togglePin');

});


// Arquivo de rotas de autenticação (login, registro, etc.)
require __DIR__.'/auth.php';

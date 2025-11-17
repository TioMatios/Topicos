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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $posts = Post::with(['user', 'comments.user', 'likedByUsers', 'community'])
    ->orderByRaw('pinned_at IS NULL')
    ->orderBy('pinned_at', 'desc')
    ->latest()
    ->paginate(10); 
    $popularCommunities = Community::withCount('members')->orderBy('members_count', 'desc')->take(5)->get();
    
     return view('dashboard', [
     'posts' => $posts,
    'popularCommunities' => $popularCommunities,
     ]);
    })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/users/{user}/follow', [ProfileController::class, 'toggleFollow'])->name('profile.toggleFollow');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

    Route::resource('communities', CommunityController::class);
    Route::post('/communities/{community}/join', [CommunityController::class, 'toggleJoin'])->name('communities.toggleJoin');

    Route::resource('posts', PostController::class)->except(['index']);
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.toggleLike');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/posts/{post}/toggle-pin', [PostController::class, 'togglePin'])->name('posts.togglePin');

});


require __DIR__.'/auth.php';

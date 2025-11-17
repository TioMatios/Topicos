<?php

namespace App\Providers;

// Adicione estas linhas
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\Community;
use App\Policies\CommunityPolicy;
use App\Models\Comment;             // <-- Adicionado
use App\Policies\CommentPolicy;      // <-- Adicionado

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Community::class, CommunityPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class); // <-- ADICIONE ESTA NOVA LINHA
    }
}
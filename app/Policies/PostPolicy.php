<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Permite que qualquer usuário logado crie um post.
     */
    public function create(User $user): bool
    {
        return true; // Todos os usuários logados podem criar posts.
    }

    /**
     * Define quem pode editar um post.
     * Regra: Todos os tipos de usuário só podem editar os próprios posts.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Define quem pode apagar um post.
     * Esta é a regra mais complexa.
     */
    public function delete(User $user, Post $post): bool
    {
        // Regra 1: O 'admin' pode apagar qualquer post.
        if ($user->role === 'admin') {
            return true;
        }

        // Regra 2: O 'admin2' pode apagar posts DENTRO de uma comunidade que ele criou.
        if ($user->role === 'admin2') {
            // Verifica se o post pertence a uma comunidade E se o usuário é dono dessa comunidade
            if ($post->community_id && $user->id === $post->community->user_id) {
                return true;
            }
        }
        
        // Regra 3: O 'user' (e os outros também) pode apagar os próprios posts.
        return $user->id === $post->user_id;
    }

    public function pin(User $user, Post $post): bool
    {
    // Só faz sentido fixar posts dentro de uma comunidade
    if (!$post->community_id) {
        return false;
    }

    // O 'admin' pode fixar em qualquer comunidade
    if ($user->role === 'admin') {
        return true;
    }

    // O 'admin2' só pode fixar na comunidade que ele criou
    return $user->id === $post->community->user_id;
    }
}
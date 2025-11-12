<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Define quem pode editar um comentário.
     * Regra: Apenas o autor do comentário.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Define quem pode apagar um comentário.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Regra 1: O 'admin' pode apagar qualquer comentário.
        if ($user->role === 'admin') {
            return true;
        }

        // Regra 2: O 'admin2' (dono da comunidade) pode apagar comentários dentro da sua comunidade.
        // Verificamos se o post do comentário pertence a uma comunidade E se o usuário é o dono dela.
        if ($user->role === 'admin2' && $comment->post->community_id) {
            if ($user->id === $comment->post->community->user_id) {
                return true;
            }
        }

        // Regra 3: O autor do comentário pode sempre apagá-lo.
        return $user->id === $comment->user_id;
    }
}
<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'admin2' && $comment->post->community_id) {
            if ($user->id === $comment->post->community->user_id) {
                return true;
            }
        }

        return $user->id === $comment->user_id;
    }
}
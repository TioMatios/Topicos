<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function create(User $user): bool
    {
        return true; 
    }

    
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'admin2') {
            
            if ($post->community_id && $user->id === $post->community->user_id) {
                return true;
            }
        }
        
     
        return $user->id === $post->user_id;
    }

    public function pin(User $user, Post $post): bool
    {
    if (!$post->community_id) {
        return false;
    }

    if ($user->role === 'admin') {
        return true;
    }

    return $user->id === $post->community->user_id;
    }
}
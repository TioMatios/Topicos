<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommunityPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'admin2']);
    }

    public function update(User $user, Community $community): bool
    {
        return $user->id === $community->user_id;
    }

    public function delete(User $user, Community $community): bool
    {
        return $user->role === 'admin' || $user->id === $community->user_id;
    }
}
<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommunityPolicy
{
    /**
     * Define quem pode criar comunidades.
     */
    public function create(User $user): bool
    {
        // Correto: Apenas 'admin' e 'admin2' podem criar.
        return in_array($user->role, ['admin', 'admin2']);
    }

    /**
     * Define quem pode editar uma comunidade.
     * MÉTODO CORRIGIDO
     */
    public function update(User $user, Community $community): bool
    {
        // Regra correta: Permite a edição se o usuário for o dono da comunidade.
        // O 'admin' principal não pode editar, apenas o 'admin2' que a criou.
        //dd('Verificando permissão de UPDATE:', 'User ID: ' . $user->id, 'Community Owner ID: ' . $community->user_id);
        return $user->id === $community->user_id;
    }

    /**
     * Define quem pode apagar uma comunidade.
     * MÉTODO CORRIGIDO
     */
    public function delete(User $user, Community $community): bool
    {
        // Regra correta: Permite a exclusão se o usuário for o 'admin' principal
        // OU se ele for o dono da comunidade.
        return $user->role === 'admin' || $user->id === $community->user_id;
    }
}
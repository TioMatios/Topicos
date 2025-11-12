<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image_profile',
        'address',
        'whatsapp',
        'instagram',
        'description_profile',
        'type', // Adicionado para gerenciar papéis
    ];

    /**
     * Os atributos que devem ser ocultados para serialização.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELACIONAMENTOS ---

    /**
     * Os posts que o usuário criou.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Os comentários que o usuário fez.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * As comunidades que o usuário criou.
     */
    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }

    /**
     * As comunidades das quais o usuário é membro.
     */
    public function joinedCommunities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class, 'community_user');
    }

    /**
     * Os posts que este usuário curtiu.
     */
    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }

    /**
     * Os usuários que este usuário segue (following).
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id');
    }

    /**
     * Os usuários que seguem este usuário (followers).
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id');
    }

    /**
     * As denúncias que este usuário fez.
     */
    public function reportsMade(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Os avisos que este usuário recebeu.
     */
    public function warningsReceived(): HasMany
    {
        return $this->hasMany(Warning::class, 'user_id');
    }

    /**
     * Os avisos que este usuário (como admin) emitiu.
     */
    public function warningsIssued(): HasMany
    {
        return $this->hasMany(Warning::class, 'admin_id');
    }
}
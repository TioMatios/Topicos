<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User; 

class Post extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'title',
        'content',
        'image_url',
        'video_url',
        'user_id',
        'community_id',
    ];

    /**
     * O usuário que criou o post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A comunidade à qual o post pertence (pode ser nulo).
     */
    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Os comentários do post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likedByUsers()
    {
    return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
    }   
}
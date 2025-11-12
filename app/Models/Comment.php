<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'content',
        'image_url',
        'user_id',
        'post_id',
    ];

    /**
     * O usuário que fez o comentário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * O post ao qual o comentário pertence.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
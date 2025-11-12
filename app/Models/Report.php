<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reason',
        'evidence_image_url',
        'reportable_id',
        'reportable_type',
    ];

    /**
     * O usuário que fez a denúncia.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Relação polimórfica: obtém o item que foi denunciado (Post, Comment, etc.).
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}
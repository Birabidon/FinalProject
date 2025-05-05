<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    // php artisan make:model Post -a
    protected $fillable = [
        'title',
        'content',
        'lat',
        'lng',
        'location',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'date:d-M-Y',
        'updated_at' => 'date:d-M-Y',
        'deleted_at' => 'date:d-M-Y',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments()
    {
        return $this->hasMany(PostAttachment::class);
    }

    public function reactions()
    {
        return $this->hasMany(PostReaction::class);
    }

    // accessor for automatic average rating calculation
    public function getAverageRatingAttribute() {
        return $this->reactions()->avg('rating');
    }

    public function getUserRatingAttribute($userId = null) {
        $userId = $userId ?: auth()->id();
        return $this->reactions()->where('user_id', $userId)->value('rating');
    }

    public function getVotesCountAttribute() {
        return $this->reactions()->count();
    }

    // $appends to include them in JSON/arrays
    protected $appends = ['average_rating', 'user_rating', 'votes_count'];
}

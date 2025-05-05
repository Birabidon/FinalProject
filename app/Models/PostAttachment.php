<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostAttachment extends Model
{
    // Disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'file_name',
        'file_path',
        'file_type',
        'created_by',
        'attachment',
    ];

    // Model events to delete physical file when record is deleted

    protected static function boot()
    {
        parent::boot();

        static::deleted(function (self $model) {
            Storage::disk('public')->delete($model->file_path);
        });
    }

    protected $casts = [
        'attachment' => 'string',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getAttachmentUrlAttribute()
    {
        return Storage::url($this->attachment);
    }
}

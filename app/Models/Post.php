<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    // php artisan make:model Post -a
    protected $fillable = [
        'title',
        'content',
        'lat',
        'lng',
        'location',
        'created_by',
        'updated_by',
        'deleted_by',
        'located_at',
    ];
}

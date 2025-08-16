<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'meta_description',
        'content',
        'slug',
        'image',
        'external_url',
        'date_published',
        'is_published'
    ];
}

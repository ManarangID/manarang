<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'seotitle', 'content', 'meta_description', 'picture', 'picture_description', 'tag', 'type', 'category_id', 'active', 'headline', 'comment', 'updated_by', 'created_by', 'updated_at', 'created_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'content', 'picture', 'album_id', 'updated_by', 'created_by', 'updated_at', 'created_at'
    ];
}

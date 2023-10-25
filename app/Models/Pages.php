<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'seotitle', 'content', 'picture', 'active', 'updated_by', 'created_by', 'updated_at', 'created_at'
    ];
}

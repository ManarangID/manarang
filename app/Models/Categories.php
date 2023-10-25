<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'seotitle', 'picture', 'active', 'updated_by', 'created_by', 'updated_at', 'created_at'
    ];
}

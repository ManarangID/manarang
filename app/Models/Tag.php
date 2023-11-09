<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory, LogsActivity;
    
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly(['title', 'seotitle','count'])
                ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
                ->useLogName('Tag');
    }
    protected $fillable = [
        'title', 'seotitle', 'count', 'created_by', 'updated_by'
    ];
}

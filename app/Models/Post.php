<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use LogsActivity, HasFactory;
	
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly(['title', 'active','content'])
                ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
                ->useLogName('Post');
    }
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    // public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'category_id', 'title', 'seotitle', 'content', 'meta_description', 'picture', 'picture_description', 'tag', 'type', 'active', 'headline', 'comment', 'hits', 'created_by', 'updated_by'
	];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

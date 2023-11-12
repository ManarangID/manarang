<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly(['name', 'email','content'])
                ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
                ->useLogName('Comment');
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
    protected $table = 'comments';

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
		'parent', 'post_id', 'name', 'email', 'content', 'active', 'status', 'created_by', 'updated_by'
	];

	public function createdBy()
	{
		return $this->belongsTo('App\Models\User', 'created_by');
	}

	public function updatedBy()
	{
		return $this->belongsTo('App\Models\User', 'updated_by');
	}

	public function mainParent() {
		return $this->hasOne('App\Models\Comment', 'id', 'parent');
	}

	public function children() {
		return $this->hasMany('App\Models\Comment', 'parent', 'id');
	}

	public static function tree($id, $limit) {
		return static::with(implode('.', array_fill(0, 100, 'children')))->where([['post_id', '=', $id],['parent', '=', '0']])->paginate($limit);
	}

	public function post()
	{
		return $this->belongsTo('App\Models\Post', 'post_id');
	}

	protected static $logAttributes = ['*'];
}

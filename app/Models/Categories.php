<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory, LogsActivity;
    
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly(['title', 'active','content'])
                ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
                ->useLogName('Categories');
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
    protected $table = 'categories';

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
		'parent', 'title', 'seotitle', 'picture', 'active', 'created_by', 'updated_by'
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
		return $this->hasOne('App\Models\Category', 'id', 'parent');
	}

	public function children() {
		return $this->hasMany('App\Models\Categories', 'parent', 'id');
	}

	public static function tree() {
		return static::with(implode('.', array_fill(0, 100, 'children')))->get();
	}
	
	public function posts() {
		return $this->hasMany('App\Models\Post', 'category_id');
	}
	
	protected static $logAttributes = ['*'];
}

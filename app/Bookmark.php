<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Bookmark extends Model
{
    protected $dates = ['time_posted', 'created_at', 'updated_at'];
    protected $guarded = ['id'];

    // Boot method - includes a global scope
    // to prevent private & unread posts to be shown, by default
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('visible', function (Builder $builder) {
            $builder->where('public', true)->where('unread', false);
        });
    }
    
    /**
     * Describes one-to-many relationship
     */
    public function tags()
	{
		return $this->belongsToMany('App\Tag');
	}
}

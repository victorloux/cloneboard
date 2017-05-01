<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use \LayerShifter\TLDExtract as TLDExtract;

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

    /**
     * Accessor that parses the URL to give only the full domain
     * It uses TLDextract to accurately process domains like "co.uk"
     * and it removes www., but will keep other subdomains
     * @return {String The host}
     */
    public function getRootUrlAttribute()
    {
        $extract = new TLDExtract\Extract();
        $result = $extract->parse($this->url);
        $result = $result->getFullHost();
        if(starts_with($result, 'www.')) {
            $result = substr($result, 4);
        }
        return $result;
    }
}

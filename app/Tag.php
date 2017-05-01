<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function bookmarks()
	{
		return $this->belongsToMany('App\Bookmark');
	}
	
	public function __toString()
	{
		return $this->tag;
	}
}

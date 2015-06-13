<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model {
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'sites';

	
	/**
	* Relationship with site type
	*/
	public function siteType()
	{
	 return $this->belongsTo('App\Models\SiteType');
	}
	/**
	* Relationship with facility
	*/
	public function facility()
	{
	 return $this->belongsTo('App\Models\Facility');
	}
	/**
	* Relationship with county
	*/
	public function county()
	{
	 return $this->belongsTo('App\Models\County');
	}
	
	
}
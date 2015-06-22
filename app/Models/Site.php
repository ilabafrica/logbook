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
	/**
	* Relationship with htc
	*/
	public function htc()
	{
		return $this->hasMany('App\Models\Htc');
	}
	/**
	* Return Site ID given the name
	* @param $name the name of the site
	*/
	public static function idByName($name)
	{
		try 
		{
			$site = Site::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
			return $site->id;
		} catch (ModelNotFoundException $e) 
		{
			Log::error("The site ` $name ` does not exist:  ". $e->getMessage());
			//TODO: send email?
			return null;
		}
	}
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Site extends Model implements Revisionable{
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'sites';
 	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'site_type_id',
        'user_id',
    ];

	
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
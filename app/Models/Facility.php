<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model {
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'facilities';

	/**
	* Operational Status
	*/
	const OPERATIONAL = 1;
	const NOTOPERATIONAL = 0;
	/**
	* Relationship with facility type
	*/
	public function facilityType()
	{
		return $this->belongsTo('App\Models\FacilityType');
	}
	/**
	* Relationship with facilityOwner
	*/
	public function facilityOwner()
	{
		return $this->belongsTo('App\Models\FacilityOwner');
	}
	/**
	* Relationship with sites
	*/
	public function sites()
	{
		return $this->hasMany('App\Models\Site');
	}
	/**
	* Relationship with subcounty
	*/
	public function subCounty()
	{
		return $this->belongsTo('App\Models\SubCounty');
	}

}
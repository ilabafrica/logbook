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
	* Relationship with town
	*/
	public function town()
	{
	 return $this->belongsTo('App\Models\Town');
	}
	/**
	* Relationship with laboratories
	*/
	public function lab()
	{
	 return $this->hasMany('App\Models\Lab');
	}
	/**
	* Relationship with job title
	*/
	public function title()
	{
	 return $this->belongsTo('App\Models\Title');
	}
	
}
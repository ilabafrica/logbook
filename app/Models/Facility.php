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
	/**
	* Return Facility ID given the name
	* @param $name the name of the facility
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$facility = Facility::where('name', $name)->orderBy('name', 'asc')->first();
				if($facility)
					return $facility->id;
				else
					return 1;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The facility ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	* Relationship with surveys
	*/
	public function surveys()
	{
		return $this->hasMany('App\Models\Survey');
	}
	/**
	* Function to get counts per checklist
	*/
	public function submissions($id)
	{
		//	Initialize counter		
		$count = 0;
		//	Get surveys and count if in array
		foreach (Checklist::find($id)->surveys as $survey) 
		{
			if(in_array($survey->facility_id, [$this->id]))
			{
				$count++;
			}
		}
		return $count;
	}
}
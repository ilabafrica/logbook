<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Facility extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'facilities';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'code',
        'name',
        'sub_county_id',
        'facility_type_id',
        'facility_owner_id',
        'reporting_site',
        'nearest_town',
        'landline',
        'mobile',
        'address',
        'in_charge',
        'operational_status',
        'longitude',
        'latitude',
        'user_id',
    ];
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
}
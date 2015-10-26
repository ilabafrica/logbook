<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class County extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'counties';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'hq',
        'user_id',
    ];
	public function subCounties()
	{
		return $this->hasMany('App\Models\SubCounty');
	}
	/**
	* Function to get counts per checklist
	*/
	public function submissions($id)
	{
		//	Initialize counter		
		$count = 0;
		//	Get facilities array
		$facilities = array();
		foreach ($this->subCounties as $subCounty) 
		{
			foreach ($subCounty->facilities as $facility) 
			{
				array_push($facilities, $facility->id);
			}
		}
		//	Get surveys and count if in array
		foreach (Checklist::find($id)->surveys as $survey) 
		{
			if(in_array($survey->facility_id, $facilities))
			{
				$count++;
			}
		}
		return $count;
	}
	/**
	*	Return facilities for a particular county
	*/	
	public function facilities()
	{
		return $this->subCounties()->join('facilities', 'facilities.sub_county_id', '=', 'sub_counties.id')->get(array('facilities.*'));
	}
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class County extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'counties';
	/**
	* Relationship with constituencies
	*/
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
}
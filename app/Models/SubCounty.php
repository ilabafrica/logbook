<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class SubCounty extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sub_counties';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'county_id',
    ];

	/**
	* Relationship with county
	*/
	public function county()
	{

		return $this->belongsTo('App\Models\County');

	}

    /**
    * Relationship with facilities
    */
    public function facilities()
    {

        return $this->hasMany('App\Models\Facility');

    }
    /**
    * Function to get counts per checklist
    */
    public function submissions($id)
    {
        //  Initialize counter      
        $count = 0;
        //  Get facilities array
        $facilities = array();
        foreach ($this->facilities as $facility) 
        {
            array_push($facilities, $facility->id);
        }
        //  Get surveys and count if in array
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
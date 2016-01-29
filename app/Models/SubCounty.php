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
    public function submissions($id, $from = null, $to = null, $year = 0, $month = 0, $date = 0)
    {
        //  Initialize counter      
        $count = 0;
        $theDate = "";
        if ($year > 0) {
            $theDate .= $year;
            if ($month > 0) {
                $theDate .= "-".sprintf("%02d", $month);
                if ($date > 0) {
                    $theDate .= "-".sprintf("%02d", $date);
                }
            }
        }
        //  Get facilities array
        $facilities = array();
        foreach ($this->facilities as $facility) 
        {
            array_push($facilities, $facility->id);
        }
        //  Get surveys and count if in array
        $surveys = Checklist::find($id)->surveys()->whereIn('facility_id', $facilities);
        if (strlen($theDate)>0 || ($from && $to))
        {
            if($from && $to)
            {
                $surveys = $surveys->whereBetween('date_submitted', [$from, $to]);
            }
            else
            {
                $surveys = $surveys->where('date_submitted', 'LIKE', $theDate."%");
            }
        }
        $surveys = $surveys->lists('surveys.id');
        return SurveySdp::whereIn('survey_id', $surveys)->count();
    }
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Checklist extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'checklists';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'description',
        'user_id',
    ];

	/**
	 * Surveys relationship
	 */
	public function surveys()
	{
		return $this->hasMany('App\Models\Survey');
	}
	/**
	 * Sections relationship
	 */
	public function sections()
	{
		return $this->hasMany('App\Models\Section');
	}	
	/**
	* Get sdps in period given
	*/
	public function ssdps($from = NULL, $to = NULL, $county = NULL, $sub_county = NULL, $site = NULL)
	{
		$ssdps =  $this->surveys()->join('survey_sdps', 'surveys.id', '=', 'survey_sdps.survey_id');
					if($from && $to)
					{
						$ssdps = $ssdps->whereBetween('date_submitted', [$from, $to]);
					}
					if($county || $sub_county || $site)
					{
						if($sub_county || $site)
						{
							if(isset($site))
							{
								$ssdps = $ssdps->where('facility_id', $site);
							}
							else
							{
								$ssdps = $ssdps->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
										 		 ->where('sub_county_id', $sub_county);
							}
						}
						else
						{
							$ssdps = $ssdps->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
											 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
											 ->where('county_id', $county);
						}
					}
					$ssdps = $ssdps->count();
		return $ssdps;
	}
	/**
	* Return Checklist ID given the name
	* @param $name the name of the user
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$checklist = Checklist::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $checklist->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The checklist ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	 * Function to calculate level
	 */
	public function level($level)
	{
		$data = SurveySdp::join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
						 ->where('surveys.checklist_id', $this->id)
						 ->get();
		$counter = 0;
		$total = 0.00;
		$total_points = $this->sections->sum('total_points');
		foreach ($data as $datum)
		{
			foreach ($datum->sqs as $sq)
			{
				if($sq->question->answers->count()>0)
					$total+=$sq->ss->score;
			}
			if(($total*100/$total_points >= $level->range_lower) && ($total*100/$total_points <= $level->range_upper))
			{
				$counter++;
			}
		}
		return round($counter/count($data), 2);
	}
	/**
	 * Count unique officers who participated in survey
	 */
	public function officers($county = null, $subCounty = null)
	{
		$data = null;
		if($county || $subCounty)
		{
			$data = $this->surveys()->join('facilities', 'facilities.id', '=', 'surveys.facility_id');
			if($subCounty)
			{
				$data = $data->where('facilities.sub_county_id', $subCounty->id);
			}
			else
			{
				$data = $data->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
							 ->where('sub_counties.county_id', $county->id);
			}
		}
		else
		{
			$data = $this->surveys;
		}
		return $data->groupBy('qa_officer')->count();
	}
	/**
	 * Return distinct facilities with submitted data in surveys
	 */
	public function distFac()
	{
		$facilities = $this->surveys->lists('facility_id');
		return array_unique($facilities);
	}
	/**
	 * Return distinct sub-counties with submitted data in surveys
	 */
	public function distSub()
	{
		$subs = array();
		$facilities = $this->distFac();
		foreach ($facilities as $facility)
		{
			array_push($subs, Facility::find($facility)->subCounty->id);
		}
		return array_unique($subs);
	}
	/**
	 * Return counties with submitted data in surveys
	 */
	public function distCount()
	{
		$counties = array();
		$subs = $this->distSub();
		foreach ($subs as $sub)
		{
			array_push($counties, SubCounty::find($sub)->county->id);
		}
		return array_unique($counties);
	}
}

<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface
use DB;
use Lang;

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
	 * Count unique officers who participated in survey
	 */
	public function officers($county = null, $subCounty = null)
	{
		$data = null;
		if($county || $subCounty)
		{
			$data = $this->surveys()->whereHas('facility', function($q) use($county, $subCounty){
				if($subCounty)
				{
					$q->where('facilities.sub_county_id', $subCounty->id);
				}
				else
				{
					$q->whereHas('subCounty', function($q) use($county){
						$q->where('county_id', $county->id);
					});
				}
				
			});
		}
		else
		{
			$data = $this->surveys;
		}
		return $data->groupBy('qa_officer')->count();
	}
	/**
	*
	*	Function to load counties with data for select lists in views
	*
	*/
	public function countiesWithData()
	{
		$counties = [];
		$cIds = [];
		foreach (array_filter(array_unique($this->surveys()->lists('facility_sdp_id'))) as $key)
		{
			$scIds = [];
			array_push($scIds, Facility::find(FacilitySdp::find($key)->facility_id)->subCounty->id);
			foreach (array_unique($scIds) as $sc)
			{
				array_push($cIds, SubCounty::find($sc)->county->id);
			}
		}
		$counties = County::whereIn('id', array_unique($cIds))->lists('name', 'id');
		return $counties;
	}
	/**
	 * Function to return level given the score
	 */
	public function levelCheck($score)
	{
		$levels = Level::all();
		foreach ($levels as $level)
		{
			if(($score<$level->range_upper+1) && ($score>=$level->range_lower))
				return $level->name.' ('.$level->range_lower.'-'.$level->range_upper.'%)';
		}
	}
	/**
	 * Function to return percent of sites in each range - percentage
	 */
	public function overallAgreement($percentage, $overAgr, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
	{
		$counter = 0;
		$range = $this->corrRange($percentage);
		$total_sites = 0;
		foreach ($overAgr as $agr)
		{
			$agreement = $agr['agreement'];
			if($agreement == 0 || $agr['year'] != $year || $agr['month'] != $month)
			{
				continue;
			}
			else
			{
				$total_sites++;
				if($agreement>100)
					$agreement = 100;
				if(($agreement>=$range['lower']) && ($agreement<$range['upper']+1) && ($agreement!=0))
					$counter++;
			}
		}
		return $total_sites>0?round($counter*100/$total_sites, 2):0.00;
	}
	/**
	 * Function to return percent of sites in each range - percentage per programatic area
	 */
	public function programatic($percentage, $kit, $fsdp, $sdp = null, $site = NULL, $sub_county = NULL, $jimbo = NULL, $year = 0, $month = 0, $date = 0, $from = null, $to = null, $point = null)
	{
		$counter = 0;
		$range = $this->corrRange($percentage);
		$total_sites = 0;
		if($sdp || $site)
			$fsdps = [$fsdp];
		else
			$fsdps = Sdp::find($fsdp)->facilitySdp->lists('id');
		foreach ($fsdps as $fsdp)
		{
			$agreement = FacilitySdp::find($fsdp)->overallAgreement($kit, $year, $month, $date, $from, $to);
			if($agreement == 0)
			{
				continue;
			}
			else
			{
				$total_sites++;
				if($agreement>100)
					$agreement = 100;
				if(($agreement>=$range['lower']) && ($agreement<$range['upper']+1) && ($agreement!=0))
					$counter++;
			}
		}
		return $total_sites>0?round($counter*100/$total_sites, 2):0.00;
	}
	/**
	 * Function to return corresponding range given the percentage
	 */
	public function corrRange($percentage)
	{
		$range = array();
		if($percentage === '<95%')
		{
			$range['lower'] = 0;
			$range['upper'] = 94;
		}
		else if($percentage === '95-98%')
		{
			$range['lower'] = 95;
			$range['upper'] = 97;
		}
		else if($percentage === '>98%')
		{
			$range['lower'] = 98;
			$range['upper'] = 100;
		}
		return $range;
	}
	/**
	 * Function to return percent of sites in each range - percentage
	 */
	public function positiveAgreement($percentage, $posAgr, $from = NULL, $to = NULL, $year = 0, $month = 0, $date = 0)
	{
		$counter = 0;
		$range = $this->corrRange($percentage);
		$total_sites = 0;
		foreach ($posAgr as $agr)
		{
			$agreement = $agr['agreement'];
			if($agreement == 0 || $agr['year'] != $year || $agr['month'] != $month)
			{
				continue;
			}
			else
			{
				$total_sites++;
				if(($agreement>=$range['lower']) && ($agreement<$range['upper']+1) && ($agreement!=0))
					$counter++;
			}
		}
		return $total_sites>0?round($counter*100/$total_sites, 2):0.00;
	}
	/**
	 * Function to return percent of sites in each range - percentage - for spirt levels
	 */
	public function level($lId, $county = NULL, $sub_county = NULL, $facility = NULL, $from = NULL, $to = NULL, $year = 0, $month = 0, $date = 0)
	{
		//	Check dates
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
		if($lId)
		{
			//	Get scores for each section
			$level = Level::find($lId);
			$counter = 0;
			$fsdps = $this->fsdps($this->id, $county, $sub_county, $facility, NULL, $from, $to, $year, $month, $date)->lists('facility_sdp_id');
			$fsdps = array_filter(array_unique($fsdps));
			$total_sites = count($fsdps);
			foreach ($fsdps as $fsdp)
			{
				$lvl = FacilitySdp::find($fsdp)->level($lId, $from, $to, $theDate);
				if($lvl>0)
					$counter++;
			}
			return $total_sites>0?round($counter*100/$total_sites, 2):0.00;
		}
		else
		{
			$counter = 0;
			$total_checklist_points = $this->sections->sum('total_points');
	        $unwanted = array(Question::idById('providersenrolled'), Question::idById('correctiveactionproviders')); //  do not contribute to total score
	        $notapplicable = Question::idById('dbsapply');  //  dbsapply will reduce total points to 65 if corresponding answer = 0
	        $surveys = $this->surveys();
	        if (strlen($theDate)>0 || ($from && $to))
	        {
	            if($from && $to)
	                $surveys = $surveys->whereBetween('date_submitted', [$from, $to]);
	            else
	                $surveys = $surveys->where('date_submitted', 'LIKE', $theDate."%");
	        }
	        $surveys = $surveys->lists('id');
	        $total_counts = count($surveys);
	        $questions = SurveyQuestion::whereIn('survey_id', $surveys)->whereNotIn('question_id', $unwanted)->whereIn('question_id', array_unique(DB::table('question_responses')->lists('question_id')))->lists('id');
	        $dbs = SurveyQuestion::whereIn('survey_id', $surveys)->where('question_id', $notapplicable)->lists('id');
	        $na = SurveyData::whereIn('survey_question_id', $dbs)->where('answer', '0')->count();
	        $calculated_points = SurveyData::whereIn('survey_question_id', $questions)->whereIn('answer', Answer::lists('score'))->sum('answer');
	        //  Begin processing
	        if($na>0)
	            $percentage = round(($calculated_points*100)/(($total_checklist_points*$total_counts)-(5*$na)), 3);
	        else
	            $percentage = round(($calculated_points*100)/$total_checklist_points*$total_counts, 3);
	        return $percentage;
		}
	}
    /**
     * Function to load fsdps given the different variables
     */
    public function fsdps($checklist, $county = null, $sub_county = null, $site = null, $sdp = null, $from = NULL, $to = NULL, $year = 0, $month = 0, $date = 0)
    {
    	//	Check dates
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
        $fsdps = [];
        $values = Survey::where('checklist_id', $checklist);
        if (strlen($theDate)>0 || ($from && $to))
		{
			if($from && $to)
			{
				if($this->id == Checklist::idByName('HTC Lab Register (MOH 362)'))
					$values = $values->whereBetween('data_month', [$from, $to]);
				else
					$values = $values->whereBetween('date_submitted', [$from, $to]);
			}
			else
			{
				if($this->id == Checklist::idByName('HTC Lab Register (MOH 362)'))
					$values = $values->where('data_month', 'LIKE', $theDate."%");
				else
					$values = $values->where('date_submitted', 'LIKE', $theDate."%");
			}
		}
        if($county || $sub_county || $site || $sdp)
        {
            if($sub_county || $site || $sdp)
            {
                if($site || $sdp)
                {                                
                    if($sdp)
                    {
                        $fsdps = [$sdp];
                    }
                    else
                    {
                    	$fsdps = Facility::find($site)->facilitySdp->lists('id');
                    }
                }
                else
                {
                    $fsdps = FacilitySdp::whereIn('facility_id', SubCounty::find($sub_county)->facilities->lists('id'))->lists('id');
                }
            }
            else
            {
            	$fsdps = FacilitySdp::whereIn('facility_id', Facility::whereIn('sub_county_id', County::find($county)->subCounties->lists('id'))->lists('id'))->lists('id');
            }
        }
        if(count($fsdps)>0)
            $values = $values->whereIn('facility_sdp_id', $fsdps);
        return $values;
    }
	/**
	 * Count number of questionnaires given qa officer filled
	 */
	public function questionnaires($checklist, $officer, $from = NULL, $to = NULL)
	{
		return $this->fsdps($checklist, NULL, NULL, NULL, NULL, $from, $to)->where('qa_officer', $officer)->count();
	}
	/**
	 * Define regions for use in the various reports
	 */
	public function regions($county = NULL, $sub_county = NULL)
	{
		$regions = [];
		if($county || $sub_county)
		{
			if($sub_county)
				$regions = SubCounty::find($sub_county)->facilities->lists('name', 'id');
			else
				$regions = County::find($county)->subCounties->lists('name', 'id');
		}
		else
		{
			$regions = $this->countiesWithData();
		}
		return $regions;
	}
	/**
	*
	*	Function to return sdps, title of chart and value of N
	*
	*/
	public function sdpsTitleN($jimbo = NULL, $sub_county = NULL, $site = NULL, $sdp = NULL, $from = NULL, $to = NULL)
	{
		$sdps = array();
		$title = '';
		$n = 0;
		$fsdps = [];
		if($jimbo || $sub_county || $site || $sdp)
		{
			if($sub_county || $site || $sdp)
			{
				if($site || $sdp)
				{
					$facility = Facility::find($site);
					if($sdp)
					{
						array_push($sdps, $sdp);
						array_push($fsdps, $sdp);
						$title = $facility->name.':<strong>'.FacilitySdp::cojoin($sdp).'</strong>';
					}
					else
					{
						$sdps = $facility->facilitySdp->lists('id');
						$title = $facility->name;
						$fsdps = $sdps;
					}
				}
				else
				{
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1);
					$sdps = FacilitySdp::whereIn('facility_id', SubCounty::find($sub_county)->facilities->lists('id'))->lists('sdp_id');
					$fsdps = FacilitySdp::whereIn('facility_id', SubCounty::find($sub_county)->facilities->lists('id'))->lists('id');
				}
			}
			else
			{
				$title = County::find($jimbo)->name.' '.Lang::choice('messages.county', 1);
				$sdps = FacilitySdp::whereIn('facility_id', Facility::whereIn('sub_county_id', County::find($jimbo)->subCounties->lists('id'))->lists('id'))->lists('sdp_id');
				$fsdps = FacilitySdp::whereIn('facility_id', Facility::whereIn('sub_county_id', County::find($jimbo)->subCounties->lists('id'))->lists('id'))->lists('id');
			}
		}
		else
		{
			$title = 'Kenya';
			$sdps = FacilitySdp::lists('sdp_id');
			$fsdps = FacilitySdp::lists('id');
		}
		$surveys = $this->surveys()->whereIn('facility_sdp_id', array_unique($fsdps));
		if($this->id == Checklist::idByName('HTC Lab Register (MOH 362)'))
			$surveys = $surveys->whereBetween('data_month', [$from, $to]);
		else
			$surveys = $surveys->whereBetween('date_submitted', [$from, $to]);
		$surveys = $surveys->lists('facility_sdp_id');
		$n = count(array_unique($surveys));
		$sdps = array_unique($sdps);
		return ['sdps' => $sdps, 'title' => $title.'(N='.$n.')'];
	}
	/**
	 * Function to return regional overall agreement
	 */
	public function regionalAgreement($percentage, $kit, $county = NULL, $sub_county = NULL, $facility = NULL, $from = NULL, $to = NULL)
	{
		//	Get scores for each section
		$range = $this->corrRange($percentage);
		$counter = 0;
		//	Get fsdps for the fiven region
		$fsdps = $this->sdpsTitleN($county, $sub_county, $facility, NULL, $from, $to)['sdps'];// fsdps($this->id, $county, $sub_county, $facility, NULL, $from, $to)->lists('facility_sdp_id');
		$total_sites = count($fsdps);
		foreach ($fsdps as $fsdp)
		{
			if($facility)
				$agreement = FacilitySdp::find($fsdp)->overallAgreement($kit, 0, 0, 0, $from, $to);
			else
				$agreement = Sdp::find($fsdp)->overallAgreement($kit, 0, 0, 0, $from, $to);
			if(($agreement>=$range['lower']) && ($agreement<$range['upper']+1) && ($agreement!=0))
				$counter++;
		}
		return $total_sites>0?round($counter*100/$total_sites, 2):0.00;
	}
}

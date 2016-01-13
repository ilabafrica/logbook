<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface
use DB;

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
	public function ssdps($from = NULL, $to = NULL, $county = NULL, $sub_county = NULL, $site = NULL, $sdp = NULL, $list = NULL, $year = 0, $month = 0, $date = 0, $point = null)
	{
		$values = null;
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
		$ssdps = $this->surveys()->select('surveys.id');
		if (strlen($theDate)>0 || ($from && $to))
		{
			if($from && $to)
			{
				if($this->id == Checklist::idByName('HTC Lab Register (MOH 362)'))
					$ssdps = $ssdps->whereBetween('data_month', [$from, $to]);
				else
					$ssdps = $ssdps->whereBetween('date_submitted', [$from, $to]);
			}
			else
			{
				if($this->id == Checklist::idByName('HTC Lab Register (MOH 362)'))
					$ssdps = $ssdps->where('data_month', 'LIKE', $theDate."%");
				else
					$ssdps = $ssdps->where('date_submitted', 'LIKE', $theDate."%");
			}
		}
		if($county || $sub_county || $site)
		{
			$ssdps = $ssdps->whereHas('facility', function($q) use($county, $sub_county, $site)
			{
				if($sub_county || $site)
				{
					if($site)
						$q->where('facility_id', $site);
					else
						$q->where('facilities.sub_county_id', $sub_county);
				}
				else
				{
					$q->whereHas('subCounty', function($q) use($county){
						$q->where('county_id', $county);
					});
				}
				
			});
		}
		$ssdps = $ssdps->lists('surveys.id');
		if($ssdps)
		{
			$values = SurveySdp::whereIn('survey_id', $ssdps);
			if($list)
			{
				if($sdp)
					$values = $values->where('sdp_id', $sdp);
				$values = $values->get();
			}
			else if($point)
			{
				$values = array_unique($values->lists('sdp_id'));
			}
			else
			{
				if($sdp)
					$values = $values->where('sdp_id', $sdp);
				$values = $values->count();

			}
		}
		return $values;
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
	public function level($county = NULL, $sub_county = NULL, $site = NULL, $sdp, $from = NULL, $to = NULL)
    {
        //  Get data to be used
        $values = $this->surveys();
        if($from && $to)
        {
            $values = $values->whereBetween('date_submitted', [$from, $to]);
        }
        if($county || $sub_county || $site)
        {
            $values = $values->whereHas('facility', function($q) use($county, $sub_county, $site)
            {
                if($sub_county || $site)
                {
                    if($site)
                        $q->where('facility_id', $site);
                    else
                        $q->where('facilities.sub_county_id', $sub_county);
                }
                else
                {
                    $q->whereHas('subCounty', function($q) use($county){
                        $q->where('county_id', $county);
                    });
                }
                
            });
        }
        $values = $values->lists('surveys.id');
        $ssdps = SurveySdp::whereIn('survey_id', $values);
        if($sdp)
            $ssdps = $ssdps->where('sdp_id', $sdp);
        $ssdps = $ssdps->get();
        //  Define variables for use
        $counter = 0;
        $total_counts = count($ssdps);
        $total_checklist_points = $this->sections->sum('total_points');
        $unwanted = array(Question::idById('providersenrolled'), Question::idById('correctiveactionproviders')); //  do not contribute to total score
        $notapplicable = Question::idById('dbsapply');  //  dbsapply will reduce total points to 65 if corresponding answer = 0
        //  Begin processing
        $percentage = 0.00;
        foreach ($ssdps as $key => $value)
        {
            $reductions = 0;
            $calculated_points = 0.00;
            $sqtns = $value->sqs()->whereNotIn('question_id', $unwanted)    //  remove non-contributive questions
                                  ->join('survey_data', 'survey_questions.id', '=', 'survey_data.survey_question_id')
                                  ->whereIn('survey_data.answer', Answer::lists('score'));
            $calculated_points = $sqtns->whereIn('question_id', array_unique(DB::table('question_responses')->lists('question_id')))->sum('answer');
            if($sq = SurveyQuestion::where('survey_sdp_id', $value->id)->where('question_id', $notapplicable)->first())
            {
                if($sq->sd->answer == '0')
                    $reductions++;
            }
            if($reductions>0)
                $percentage = round(($calculated_points*100)/($total_checklist_points-5), 2);
            else
                $percentage = round(($calculated_points*100)/$total_checklist_points, 2);
        }
        return $percentage;
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
	public function overallAgreement($percentage, $kit, $sdp = null, $site = NULL, $sub_county = NULL, $jimbo = NULL, $year = 0, $month = 0, $date = 0, $from = null, $to = null, $point = null)
	{
		/*Get ssdps by geograhical region*/
		if($point)
			$ssdps = $this->ssdps(null, null, $jimbo, $sub_county, $site, $sdp, null, $year, $month, $date, 1);
		else
			$ssdps = $this->ssdps($from, $to, $jimbo, $sub_county, $site, $sdp, 1, $year, $month, $date);
		// dd($ssdps);
		//	Get scores for each section
		$counter = 0;
		$range = $this->corrRange($percentage);
		$total_sites = 0;
		if(count($ssdps)>0)
		{
			foreach ($ssdps as $ssdp)
			{
				if($point)
					$agreement = Sdp::find($ssdp)->overallAgreement($kit, $site, $sub_county, $jimbo, $year, $month, $date, $from, $to);
				else
					$agreement = SurveySdp::find($ssdp->id)->overallAgreement();
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
	 * Function to return sdp with corresponding percentage
	 */
	public function sdpOverAgreement($label, $sdps, $kit, $site = NULL, $sub_county = NULL, $jimbo = NULL, $year = 0, $month = 0, $date = 0)
	{
		//	Split label to create variables
		$array = explode("_", $label);
		//	Get scores for each section
		$counter = 0;
		$range = $this->corrRange($array[0]);
		$year = $array[2];
		$month = $array[1];
		$total_sites = count($sdps);
		$matched = array();
		foreach ($sdps as $sdp)
		{
			$point = Sdp::find($sdp);
			$agreement = $point->overallAgreement($kit, $site, $sub_county, $jimbo, $year, $month);
			if(($agreement>=$range['lower']) && ($agreement<$range['upper']+1) || (($range['lower']==0.00) && ($agreement==$range['lower'])))
				$matched[$point->name] = $agreement;
				//$matched = array_merge($matched, ["sdp"=>$point->name, "per"=>$agreement]);
		}
		return $matched;
	}
	/**
	 * Function to return percent of sites in each range - percentage
	 */
	public function positivePercent($percentage, $sdps, $site = NULL, $sub_county = NULL, $jimbo = NULL, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
	{
		//	Get scores for each section
		$counter = 0;
		$range = $this->corrRange($percentage);
		$total_sites = count($sdps);	
		foreach ($sdps as $sdp)
		{
			$agreement = Sdp::find($sdp)->positivePercent($site, $sub_county, $jimbo, $year, $month);
			if($agreement == 0)
				$total_sites--;
			if(($agreement>=$range['lower']) && ($agreement<$range['upper']+1) || (($range['lower']==0.00) && ($agreement==$range['lower'])))
				$counter++;
		}
		return $total_sites>0?round($counter*100/$total_sites, 2):0.00;
	}
	/**
	 * Function to return sdp with corresponding percentage
	 */
	public function sdpPosPercent($label, $sdps, $site = NULL, $sub_county = NULL, $jimbo = NULL, $year = 0, $month = 0, $date = 0)
	{
		//	Split label to create variables
		$array = explode("_", $label);
		//	Get scores for each section
		$counter = 0;
		$range = $this->corrRange($array[0]);
		$year = $array[2];
		$month = $array[1];
		$total_sites = count($sdps);
		$matched = array();
		foreach ($sdps as $sdp)
		{
			$point = Sdp::find($sdp);
			$agreement = $point->positivePercent($site, $sub_county, $jimbo, $year, $month);
			if(($agreement>=$range['lower']) && ($agreement<$range['upper']+1) || (($range['lower']==0.00) && ($agreement==$range['lower'])))
				$matched[$point->name] = $agreement;
				//$matched = array_merge($matched, ["sdp"=>$point->name, "per"=>$agreement]);
		}
		return $matched;
	}
	/**
	 * Function to return percent of sites in each range - percentage
	 */
	public function positiveAgreement($percentage, $sdps, $kit, $site = NULL, $sub_county = NULL, $jimbo = NULL, $year = 0, $month = 0, $date = 0)
	{
		/*Get sdps*/
		$ssdps = $this->ssdps(null, null, $jimbo, $sub_county, $site, null, null, $year, $month, $date, 1);
		//	Get scores for each section
		$counter = 0;
		$range = $this->corrRange($percentage);
		$total_sites = 0;	
		foreach ($ssdps as $sdp)
		{
			$agreement = Sdp::find($sdp)->positiveAgreement($kit, $site, $sub_county, $jimbo, $year, $month);
			if($agreement>100)
				$agreement=100.00;
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
	 * Function to return sdp with corresponding percentage
	 */
	public function sdpPosAgreement($label, $sdps, $kit, $site = NULL, $sub_county = NULL, $jimbo = NULL, $year = 0, $month = 0, $date = 0)
	{
		//	Split label to create variables
		$array = explode("_", $label);
		//	Get scores for each section
		$counter = 0;
		$range = $this->corrRange($array[0]);
		$year = $array[2];
		$month = $array[1];
		$total_sites = count($sdps);
		$matched = array();
		foreach ($sdps as $sdp)
		{
			$point = Sdp::find($sdp);
			$agreement = $point->positiveAgreement($kit, $site, $sub_county, $jimbo, $year, $month);
			if(($agreement>=$range['lower']) && ($agreement<$range['upper']+1) || (($range['lower']==0.00) && ($agreement==$range['lower'])))
				$matched[$point->name] = $agreement;
				//$matched = array_merge($matched, ["sdp"=>$point->name, "per"=>$agreement]);
		}
		return $matched;
	}
	/**
	 * Function to return percent of sites in each range - percentage - for spirt levels
	 */
	public function spirtLevel($ssdps, $level)
	{
		// dd($ssdps);
		//	Get scores for each section
		$counter = 0;
		$total_sites = count($ssdps);
		if($total_sites>0)
		{
			foreach ($ssdps as $ssdp)
			{
				$lvl = $level->spirtLevel($this->id, $ssdp);
				if(($lvl>=$level->range_lower) && ($lvl<$level->range_upper+1) || (($level->range_lower==0.00) && ($lvl==$level->range_lower)))
					$counter++;
			}
		}
		return $total_sites>0?round($counter*100/$total_sites, 2):0.00;
	}
}

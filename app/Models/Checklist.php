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
	 * Function to calculate level
	 */
	public function level($county = NULL, $sub_county = NULL, $site = NULL, $sdp, $from = NULL, $to = NULL)
    {
        //  Get data to be used
        $fsdps = $this->fsdps($this->id, $county, $sub_county, $site, $sdp, $from, $to)->get();
        //  Define variables for use
        $counter = 0;
        $total_counts = count($fsdps);
        $total_checklist_points = $this->sections->sum('total_points');
        $unwanted = array(Question::idById('providersenrolled'), Question::idById('correctiveactionproviders')); //  do not contribute to total score
        $notapplicable = Question::idById('dbsapply');  //  dbsapply will reduce total points to 65 if corresponding answer = 0
        //  Begin processing
        $percentage = 0.00;
        foreach ($fsdps as $key => $value)
        {
            $reductions = 0;
            $calculated_points = 0.00;
            $sqtns = $value->sqs()->whereNotIn('question_id', $unwanted)    //  remove non-contributive questions
                                  ->join('survey_data', 'survey_questions.id', '=', 'survey_data.survey_question_id')
                                  ->whereIn('survey_data.answer', Answer::lists('score'));
            $calculated_points = $sqtns->whereIn('question_id', array_unique(DB::table('question_responses')->lists('question_id')))->sum('answer');
            if($sq = SurveyQuestion::where('survey_id', $value->id)->where('question_id', $notapplicable)->first())
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
		if($site || $sdp)
			$surveys = $this->fsdps($this->id, $jimbo, $sub_county, $site, $sdp, $from, $to)->where('facility_sdp_id', $fsdp)->get();
		else
			$surveys = $this->fsdps($this->id, $jimbo, $sub_county, $site, $sdp, $from, $to)->whereIn('facility_sdp_id', Sdp::find($fsdp)->facilitySdp->lists('id'))->get();
		foreach ($surveys as $survey)
		{
			$agreement = 0;
			$agreement = $survey->overallAgreement($kit);
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
	public function spirtLevel($lId, $sdp = NULL, $site = NULL, $sub_county = NULL, $jimbo = NULL, $year = 0, $month = 0, $date = 0, $from = NULL, $to = NULL)
	{
		//	Get scores for each section
		$level = Level::find($lId);
		$counter = 0;
		$fsdps = $this->fsdps($this->id, $jimbo, $sub_county, $site, $sdp, $from, $to)->get();
		$total_sites = count($fsdps);
		if($total_sites>0)
		{
			foreach ($fsdps as $fsdp)
			{
				$lvl = $fsdp->level();
				if(($lvl>=$level->range_lower) && ($lvl<$level->range_upper+1) && ($lvl!=0))
					$counter++;
			}
		}
		return $total_sites>0?round($counter*100/$total_sites, 2):0.00;
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
}

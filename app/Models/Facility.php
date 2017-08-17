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
	* Relationship with facility-sdp
	*/
	public function facilitySdp()
	{
		return $this->hasMany('App\Models\FacilitySdp');
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
	public function submissions($id, $from = null, $to = null, $year = 0, $month = 0, $date = 0)
	{
		//	Initialize counter		
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
		//	Get facility-sdps for the facility
		$fsdps = $this->facilitySdp->lists('id');
		$surveys = Checklist::find($id)->surveys()->whereIn('facility_sdp_id', $fsdps);
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
	/**
	* Function to get counts per checklist
	*/
	public function perchecklist($id)
	{
		//	Initialize counter		
		return $this->surveys->where('checklist_id', $id);
	}	
	/**
	* Function to get counts per checklist for sdps
	*/
	public function sdps($id, $sdpId = null, $from = null, $to = null)
	{
		//	Initialize counter
		$counter  =  SurveySdp::join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
						->where('facility_id', $this->id)
						->where('checklist_id', $id);
						if($sdpId)
						{
							$counter = $counter->where('sdp_id', $sdpId);
						}
						if($from && $to)
						{
							if($id == Checklist::idByName('HTC Lab Register (MOH 362)'))
								$counter = $counter->whereRaw('IFNULL(data_month, date_submitted) BETWEEN '.$from.' AND '.$to->format('Y-m-d H:i:s'));
							else
								$counter = $counter->whereBetween('date_submitted', [$from, $to]);
								
						}
						$counter = $counter->count();
		return $counter;
	}
	/**
	* Calculation of positive percent[ (Total Number of Positive Results/Total Number of Specimens Tested)*100 ] - Aggregated
	*/
	public function positivePercent($sdp, $checklist = NULL, $from = NULL, $to = NULL, $testTypeID = NULL, $name = NULL, $surveys = NULL)
	{
		//	Initialize counts
		$positive = 0;
		$total = 0;
		//	Declare questions to be used in calculation of both positive and total values
		$qstns = array('Test-1 Total Positive', 'Test-1 Total Negative', 'Test-2 Total Positive', 'Test-3 Total Positive');
		//	Get the counts
		foreach ($qstns as $qstn) {
			$question = Question::idByName($qstn);
			$values = HtcSurveyPageQuestion::where('question_id', $question)
											->join('htc_survey_pages', 'htc_survey_pages.id', '=', 'htc_survey_page_questions.htc_survey_page_id')
											->join('survey_sdps', 'survey_sdps.id', '=', 'htc_survey_pages.survey_sdp_id')
											->where('sdp_id', $sdp)
											->get(array('htc_survey_page_questions.*'));
			foreach ($values as $key => $value) 
			{
				if(substr_count(Question::nameById($value->question_id), 'Test-1')>0)
					$total+=HtcSurveyPageQuestion::find($value->id)->data->answer;
				else
					$positive+=HtcSurveyPageQuestion::find($value->id)->data->answer;
			}			
		}
		return $total>0?round((int)$positive*100/(int)$total, 2):0;
	}
	/**
	* Calculation of positive agreement[ (Total Reactive Results from Test 2/Total Reactive Results from Test 1)*100 ]
	*/
	public function positiveAgreement($sdp)
	{
		//	Initialize counts
		$testOne = 0;
		$testTwo = 0;
		//	Declare questions to be used in calculation of both values
		$qstns = array('Test-1 Total Positive', 'Test-2 Total Positive');
		foreach ($qstns as $qstn) 
		{
			$question = Question::idByName($qstn);
			$values = HtcSurveyPageQuestion::where('question_id', $question)
											->join('htc_survey_pages', 'htc_survey_pages.id', '=', 'htc_survey_page_questions.htc_survey_page_id')
											->join('survey_sdps', 'survey_sdps.id', '=', 'htc_survey_pages.survey_sdp_id')
											->where('sdp_id', $sdp)
											->get(array('htc_survey_page_questions.*'));
			foreach ($values as $key => $value) 
			{
				if(substr_count(Question::nameById($value->question_id), 'Test-1')>0)
					$testOne+=HtcSurveyPageQuestion::find($value->id)->data->answer;
				else
					$testTwo+=HtcSurveyPageQuestion::find($value->id)->data->answer;
			}			
		}
		return $testOne>0?round((int)$testTwo*100/(int)$testOne, 2):0;
	}
	/**
	* Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
	*/
	public function overallAgreement($sdp)
	{
		//	Initialize variables
		$total = 0;
		$invalid = 0;
		$reactiveOne = 0;
		$nonReactiveOne = 0;
		$reactiveTwo = 0;
		$nonReactiveTwo = 0;
		//	Get sdp surveys
		$surveys = $this->surveys->lists('id');
		//	Get questions to be used in the math
		$qstns = array('Test-1 Total Positive', 'Test-1 Total Negative', 'Test-1 Total Invalid', 'Test-2 Total Positive', 'Test-2 Total Negative', 'Test-2 Total Invalid');
		//	Math
		foreach ($qstns as $qstn) 
		{
			$question = Question::idByName($qstn);
			$values = HtcSurveyPageQuestion::where('question_id', $question)
											->join('htc_survey_pages', 'htc_survey_pages.id', '=', 'htc_survey_page_questions.htc_survey_page_id')
											->join('survey_sdps', 'survey_sdps.id', '=', 'htc_survey_pages.survey_sdp_id')
											->where('sdp_id', $sdp)
											->get(array('htc_survey_page_questions.*'));
			foreach ($values as $key => $value) 
			{
				if(substr_count(Question::nameById($value->question_id), 'Test-1')>0)
					$total+=HtcSurveyPageQuestion::find($value->id)->data->answer;
				if(substr_count(Question::nameById($value->question_id), 'Invalid')>0)
					$invalid+=HtcSurveyPageQuestion::find($value->id)->data->answer;
				if(substr_count(Question::nameById($value->question_id), 'Test-1 Total Positive')>0)
					$reactiveOne+=HtcSurveyPageQuestion::find($value->id)->data->answer;
				if(substr_count(Question::nameById($value->question_id), 'Test-1 Total Negative')>0)
					$nonReactiveOne+=HtcSurveyPageQuestion::find($value->id)->data->answer;
				if(substr_count(Question::nameById($value->question_id), 'Test-2 Total Positive')>0)
					$reactiveTwo+=HtcSurveyPageQuestion::find($value->id)->data->answer;
				if(substr_count(Question::nameById($value->question_id), 'Test-2 Total Negative')>0)
					$nonReactiveTwo+=HtcSurveyPageQuestion::find($value->id)->data->answer;
			}			
		}
		$absReactive = abs($reactiveTwo-$reactiveOne);
		$absNonReactive = abs($nonReactiveTwo-$nonReactiveOne);
		/*if($algorithm == 'Parallel')
			return ($total - $invalid)>0?round((($total - $invalid) - ($absReactive + $absNonReactive)) * 100 / ($total - $invalid), 2):0;
		else*/
			return ($total - $invalid)>0?round(($reactiveTwo+$nonReactiveOne) * 100 / ($total-$invalid), 2):0;
	}	
	/**
	* Function to return unique sdps submitted for given facility
	*/
	public function ssdps($id = null, $unique = null, $from = null, $to = null)
	{
		//	Initialize counter
		$ssdps = $this->surveys()->join('survey_sdps', 'surveys.id', '=', 'survey_sdps.survey_id');
						if($id)
						{
							$ssdps = $ssdps->where('checklist_id', $id);
						}
						if($from && $to)
						{
							if($id == Checklist::idByName('HTC Lab Register (MOH 362)'))
								$ssdps = $ssdps->whereRaw('IFNULL(data_month, date_submitted) BETWEEN '.$from.' AND '.$to->format('Y-m-d H:i:s'));
							else
								$ssdps = $ssdps->whereBetween('date_submitted', [$from, $to]);
								
						}
						$ssdps = $ssdps->lists('sdp_id');
		if($unique)
			return array_unique($ssdps);
		else
			return $ssdps;
	}
	/**
	* Function to return unique sdps submitted for given facility for use in dropdown
	*/
	public function points($id, $i=null)
	{
		$in_surveys = [];
		$returnable = [];
		$cojoined = null;
		//	Get surveys_sdps
		$surveys = Survey::whereIn('facility_sdp_id', $this->facilitySdp->lists('id'));
		$checklist = Checklist::find($id);
		foreach ($this->facilitySdp as $fsdp)
		{
			if(in_array($fsdp->id, $checklist->surveys()->lists('facility_sdp_id')))
				$in_surveys[] = $fsdp->id;
		}
		//	Get survey-sdps with the above IDs
		foreach (array_unique($in_surveys) as $id)
		{
			$fsdp = FacilitySdp::find($id);
			$sdp = Sdp::find($fsdp->sdp_id);
			if($fsdp->sdp_tier_id)
				$cojoined = $sdp->name.' - '.Tier::find($fsdp->sdp_tier_id)->name;
			else
				$cojoined = $sdp->name;
			if($i)
				$returnable[$id] = $cojoined;
			else
				$returnable[] = ['name' => $cojoined, 'id' => $id];
		}
		return $returnable;
	}
	/**
	* Function to count the number of submissions for the given sdp
	*/
	public function perSdp($id)
	{
		$split = Sdp::splitSdp($id);
		$sdp_id = $split['sdp_id'];
		$comment = $split['comment'];

		$surveys = $this->surveys->lists('id');
		$ssdps = SurveySdp::whereIn('survey_id', $surveys)->where('sdp_id', $sdp_id);
		if($comment)
			$ssdps = $ssdps->where('comment', 'like', '%' . $comment . '%');
		return $ssdps->count();
	}
}
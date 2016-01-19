<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Sdp extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sdps';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'description',
    ];

	/**
	 * sdp-tiers relationship
	 */
	public function tiers()
	{
		return $this->hasMany('App\Models\Tier');
	}
	/**
	* Return Sdp ID given the name
	* @param $name the name of the user
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$sdp = Sdp::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $sdp->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The sdp ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	* Calculation of positive percent[ (Total Number of Positive Results/Total Number of Specimens Tested)*100 ] - Aggregated
	*/
	public function positivePercent($comment = NULL, $facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0, $from = NULL, $to = NULL)
	{
		//	Initialize counts
		$positive = 0;
		$total = 0;		
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
		//	Declare questions to be used in calculation of both values
		$posOne = Question::idByName('Test-1 Total Positive');
		$negOne = Question::idByName('Test-1 Total Negative');
		$posTwo = Question::idByName('Test-2 Total Positive');
		$posThree = Question::idByName('Test-3 Total Positive');
		$totals = [$posOne, $negOne];
		$positives = [$posOne, $posTwo, $posThree];
		//	Get the counts
		
		$total = $this->eagerLoad($comment, $facility, $subCounty, $county, $theDate, $from = NULL, $to = NULL, $totals, $this->id);
		$positive = $this->eagerLoad($comment, $facility, $subCounty, $county, $theDate, $from = NULL, $to = NULL, $positives, $this->id);
		return $total>0?round((int)$positive*100/(int)$total, 2):0;
	}
	/**
	* Calculation of positive agreement[ (Total Reactive Results from Test 2/Total Reactive Results from Test 1)*100 ]
	*/
	public function positiveAgreement($kit, $facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
	{
		//	Initialize counts
		$testOne = 0;
		$testTwo = 0;		
		/*pages*/
		$pages = $this->eagerPages($kit, $facility, $subCounty, $county, $year, $month, $date, $from, $to);
		/*htc survey page questions*/
		$quest = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $pages);
		//	Declare questions to be used in calculation of both values
		$posOne = Question::idByName('Test-1 Total Positive');
		$posTwo = Question::idByName('Test-2 Total Positive');
		//	For each of the pages, get to data and add the given values
		$one = clone $quest; $two = clone $quest;
		$testOne = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $one->where('question_id', $posOne)->lists('id'))->sum('answer');
		$testTwo = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $two->where('question_id', $posTwo)->lists('id'))->sum('answer');
		return $testOne>0?round((int)$testTwo*100/(int)$testOne, 2):0.00;
	}
	/**
	* Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
	*/
	public function overallAgreement($kit, $facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
	{
		//	Initialize variables
		$total = 0;
		$invalid = 0;
		$reactiveOne = 0;
		$nonReactiveOne = 0;
		$reactiveTwo = 0;
		$nonReactiveTwo = 0;		
		/*pages*/
		$pages = $this->eagerPages($kit, $facility, $subCounty, $county, $year, $month, $date, $from, $to);
		/*htc survey page questions*/
		$quest = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $pages);
		/*htc survey data*/
	  	//	Get questions to be used in the math
		$testOnePos = Question::idByName('Test-1 Total Positive');
		$testOneNeg = Question::idByName('Test-1 Total Negative');
		$testOneInv = Question::idByName('Test-1 Total Invalid');
		$testTwoPos = Question::idByName('Test-2 Total Positive');
		$testTwoNeg = Question::idByName('Test-2 Total Negative');
		$testTwoInv = Question::idByName('Test-2 Total Invalid');
		$totalTestOne = [$testOnePos, $testOneNeg, $testOneInv];
		$invalids = [$testOneInv, $testTwoInv];
		//	Math
		$one = clone $quest; $two = clone $quest; $three = clone $quest; $four = clone $quest; $five = clone $quest; $six = clone $quest;
		$total = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $one->whereIn('question_id', $totalTestOne)->lists('id'))->sum('answer');
		$invalid = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $two->whereIn('question_id', $invalids)->lists('id'))->sum('answer');
		$reactiveOne = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $three->where('question_id', $testOnePos)->lists('id'))->sum('answer');
		$nonReactiveOne = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $four->where('question_id', $testOneNeg)->lists('id'))->sum('answer');
		$reactiveTwo = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $five->where('question_id', $testTwoPos)->lists('id'))->sum('answer');
		$nonReactiveTwo = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $six->where('question_id', $testTwoNeg)->lists('id'))->sum('answer');
		
		$absReactive = abs($reactiveTwo-$reactiveOne);
		$absNonReactive = abs($nonReactiveTwo-$nonReactiveOne);
		return ($total - $invalid)>0?round(($reactiveTwo+$nonReactiveOne) * 100 / ($total-$invalid), 2):0;
	}
	/**
	* Return Sdp ID given the identifier
	* @param $name the identifier of the sdp
	*/
	public static function idById($id=NULL)
	{
		if($id!=NULL){
			try 
			{
				$sdp = Sdp::where('identifier', $id)->orderBy('name', 'asc')->firstOrFail();
				return $sdp->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The sdp with identifier ` $id ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	* Function to return counts of data submiited
	*/
	public function submissions($id, $check, $from = null, $to = null, $year = 0, $month = 0, $date = 0)
	{
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
		$ssdps = $this->surveys()->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
				  ->where('facility_id', $id)
				  ->where('checklist_id', $check);
				  if (strlen($theDate)>0 || ($from && $to))
					{
						if($from && $to)
						{
							$ssdps = $ssdps->whereBetween('date_submitted', [$from, $to]);
						}
						else
						{
							$ssdps = $ssdps->where('date_submitted', 'LIKE', $theDate."%");
						}
					}
		return $ssdps->groupBy('sdp_id')->count();
	}
	/**
	*	Function to eager-load questions for use in calculating other derivatives
	*/
	public function eagerLoad($comment = NULL, $facility = null, $subCounty = null, $county = null, $theDate, $from = NULL, $to = NULL, $array, $sdp_id)
	{
		$data = HtcSurveyPageData::whereHas('htc_survey_page_question', function($q) use ($comment, $facility, $subCounty, $county, $theDate, $from, $to, $array, $sdp_id){
			
			$q->whereIn('question_id', $array)->whereHas('htc_survey_page', function($q) use ($comment, $facility, $subCounty, $county, $theDate, $from, $to, $sdp_id){
				$q->whereHas('survey_sdp', function($q) use ($comment, $facility, $subCounty, $county, $theDate, $from, $to, $sdp_id){
					$q->whereHas('survey', function($q) use ($facility, $subCounty, $county, $theDate, $from, $to){
						if($county || $subCounty || $facility)
						{
							if($subCounty || $facility)
							{
								if($facility)
								{
									$q->where('facility_id', $facility);
								}
								else
								{
									$q->whereHas('facility', function($q) use($subCounty){
										$q->where('sub_county_id', $subCounty);
									});
								}
							}
							else
							{
								$q->whereHas('facility', function($q) use($county){
									$q->whereHas('subCounty', function($q) use ($county){
										$q->where('county_id', $county);
									});
								});
							}
						}
						if(strlen($theDate)>0 || ($from && $to))
						{
							if($from && $to)
							{
								$q->whereBetween('data_month', [$from, $to]);
							}
							else
							{
								$q->where('data_month', 'LIKE', $theDate."%");
							}	
						}
					})->where('sdp_id', $this->id)->where('comment', 'like', '%' . $comment . '%');
				});
			});
		});
		return $data->sum('answer');
	}
	/**
	*
	*	Function to eager load data for use in positive/overall agreement
	*
	*/
	public function eagerPages($kit, $facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
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
		$screen = Question::idById('screen');	//	Question whose response is either determine or khb
		//	Get pages whose screening test is as given(khb/determine)
		/*Checklist*/
		$checklist = Checklist::idByName('HTC Lab Register (MOH 362)');
		/*Surveys*/
		$surveys = Survey::where('checklist_id', $checklist);
		if (strlen($theDate)>0 || ($from && $to)) {
	  		if($from && $to)
	  		{
	  			$surveys = $surveys->whereBetween('data_month', [$from, $to]);
	  		}
	  		else
	  		{
	  			$surveys = $surveys->where('data_month', 'LIKE', $theDate."%");
	  		}								
	  	}
	  	if($county || $subCounty || $facility)
		{
			$surveys = $surveys->whereHas('facility', function($q) use($county, $subCounty, $facility)
			{
				if($subCounty || $facility)
				{
					if($facility)
						$q->where('facility_id', $facility);
					else
						$q->where('facilities.sub_county_id', $subCounty);
				}
				else
				{
					$q->whereHas('subCounty', function($q) use($county){
						$q->where('county_id', $county);
					});
				}
				
			});
		}
		$surveys = $surveys->lists('surveys.id');

		/*survey sdps*/
		$ssdps = SurveySdp::whereIn('survey_id', $surveys)->where('sdp_id', $this->id)->lists('id');
		/*htc survey pages*/
		$pages = HtcSurveyPage::select('htc_survey_pages.id')
								->whereIn('survey_sdp_id', $ssdps)
								->join('htc_survey_page_questions', 'htc_survey_pages.id', '=', 'htc_survey_page_questions.htc_survey_page_id')
								->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')
								->where('question_id', $screen)
								->where('answer', $kit)
								->lists('htc_survey_pages.id');
		return $pages;
	}
	/**
	* Function to split given string to get sdp and comment
	*/
	public static function splitSdp($id)
	{
		$sdpName = '';
		$comment = null;
		if(stripos($id, '-') !==FALSE)
		{
			$id = explode('-', $id);
			$sdpName = $id[0];
			if(trim($id[1])!='')
				$comment = $id[1];
		}
		else
			$sdpName = $id;
		$sdp_id = Sdp::idByName($sdpName);
		$comment = trim($comment);
		return ['sdp_id' => $sdp_id, 'comment' => $comment];
	}
}
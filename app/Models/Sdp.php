<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface
use DB;

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
    ];

	/**
	 * sdp-tiers relationship
	 */
	public function tiers()
	{
		return $this->hasMany('App\Models\Tier');
	}
	/**
	 * facility-sdp relationship
	 */
	public function facilitySdp()
	{
		return $this->hasMany('App\Models\FacilitySdp');
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
	public function positivePercent($facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0, $from = NULL, $to = NULL)
	{
		$fsdps = $this->facilitySdp->lists('id');
		$checklist = Checklist::idByName('HTC Lab Register (MOH 362)');
        $surveys = Checklist::find($checklist)->fsdps($checklist, $county, $subCounty, NULL, NULL, $from, $to, $year, $month, $date)->whereIn('facility_sdp_id', $fsdps)->lists('id');
        //  Initialize counts
        $positive = 0;
        $total = 0;     
        //  Declare questions to be used in calculation of both values
        $posOne = Question::idByName('Test-1 Total Positive');
        $negOne = Question::idByName('Test-1 Total Negative');
        $posTwo = Question::idByName('Test-2 Total Positive');
        $posThree = Question::idByName('Test-3 Total Positive');
        $totals = [$posOne, $negOne];
        $positives = [$posOne, $posTwo, $posThree];
        //  Get the counts        
        $total = $this->eagerLoad($surveys, $totals);
        $positive = $this->eagerLoad($surveys, $positives);
        return $total>0?round((int)$positive*100/(int)$total, 2):0;
	}
	/**
	* Calculation of positive agreement[ (Total Reactive Results from Test 2/Total Reactive Results from Test 1)*100 ]
	*/
	public function positiveAgreement($kit, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
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
		//	Initialize counts
		$testOne = 0;
		$testTwo = 0;
		/*pages*/
		$fsdps = $this->facilitySdp->lists('id');
		$checklist = Checklist::idByName('HTC Lab Register (MOH 362)');
		$surveys = Survey::where('checklist_id', $checklist)->whereIn('facility_sdp_id', $fsdps);
		if (strlen($theDate)>0 || ($from && $to))
		{
			if($from && $to)
				$surveys = $surveys->whereBetween('data_month', [$from, $to]);
			else
				$surveys = $surveys->where('data_month', 'LIKE', $theDate."%");
		}
		$surveys = $surveys->lists('id');
		$pages =  HtcSurveyPage::whereIn('survey_id', $surveys)->lists('id');
		//	Get pages with the screening question being answered by kit
		$refinedPages = [];
        $screen = Question::idById('screen');   //  Question whose response is either determine or khb
        // work in reverse to get pages
        $data = HtcSurveyPageData::where('answer', $kit)->lists('htc_survey_page_question_id');
        $refinedIds = HtcSurveyPageQuestion::whereIn('id', $data)->where('question_id', $screen)->lists('htc_survey_page_id');
        $refinedPages = array_intersect($pages, $refinedIds);
		/*htc survey page questions*/
		$quest = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $refinedPages);
		//	Declare questions to be used in calculation of both values
		$posOne = Question::idByName('Test-1 Total Positive');
		$posTwo = Question::idByName('Test-2 Total Positive');
		//	For each of the pages, get to data and add the given values
		$one = clone $quest; $two = clone $quest;
		$testOne = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $one->where('question_id', $posOne)->lists('id'))->sum('answer');
		$testTwo = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $two->where('question_id', $posTwo)->lists('id'))->sum('answer');
		return $testOne>0?(round((int)$testTwo*100/(int)$testOne, 2)>100?100:round((int)$testTwo*100/(int)$testOne, 2)):0.00;
	}
	/**
	* Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
	*/
	public function overallAgreement($kit, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
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
		//	Initialize variables
		$total = 0;
		$invalid = 0;
		$reactiveOne = 0;
		$nonReactiveOne = 0;
		$reactiveTwo = 0;
		$nonReactiveTwo = 0;		
		/*pages*/
		$fsdps = $this->facilitySdp->lists('id');
		$checklist = Checklist::idByName('HTC Lab Register (MOH 362)');
        $surveys = Survey::where('checklist_id', $checklist)->whereIn('facility_sdp_id', $fsdps);
		if (strlen($theDate)>0 || ($from && $to))
		{
			if($from && $to)
				$surveys = $surveys->whereBetween('data_month', [$from, $to]);
			else
				$surveys = $surveys->where('data_month', 'LIKE', $theDate."%");
		}
		$surveys = $surveys->lists('id');
		$pages =  HtcSurveyPage::whereIn('survey_id', $surveys)->lists('id');
		//	Get pages with the screening question being answered by kit
		$refinedPages = [];
        $screen = Question::idById('screen');   //  Question whose response is either determine or khb
        // work in reverse to get pages
        $data = HtcSurveyPageData::where('answer', $kit)->lists('htc_survey_page_question_id');
        $refinedIds = HtcSurveyPageQuestion::whereIn('id', $data)->where('question_id', $screen)->lists('htc_survey_page_id');
        $refinedPages = array_intersect($pages, $refinedIds);
		/*htc survey page questions*/
		$quest = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $refinedPages);
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
		$percentage = 0.00;
		if(($total - $invalid)>0)
			$percentage = round(($reactiveTwo+$nonReactiveOne) * 100 / ($total-$invalid), 2);
		if($percentage>100)
			$percentage = 100;
		return $percentage;
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
	*	Function to eager-load questions for use in calculating other derivatives
	*/
    public function eagerLoad($surveys, $qstns)
    {
        $pages = HtcSurveyPage::whereIn('survey_id', $surveys)->lists('id');
        $questions = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $pages)->whereIn('question_id', $qstns)->lists('id');
        $data = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $questions)->sum('answer');
        return $data;
    }
    /**
     * Function to calculate percentage of submissions in each level and sdp for spirt
     */
    public function level($lvl, $county = null, $sub_county = null, $site = null, $sdp = null, $from = NULL, $to = NULL)
    {
    	$fsdps = $this->facilitySdp;
    	//  Define variables for use
    	$level = Level::find($lvl);
        $counter = 0;
        $total_counts = count($fsdps);
        //  Begin processing
        foreach ($fsdps as $fsdp)
        {
        	$percentage = $fsdp->level($lvl, $from, $to);
            //  Check and increment counter
            if($percentage!=0)
                $counter++;
        }
        return $total_counts > 0?round($counter*100/$total_counts, 2):0.00;
    }
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface
use DB;

class Survey extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'surveys';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'qa_officer',
        'facility_id',
        'longitude',
        'latitude',
        'checklist_id',
        'comment',
        'user_id',
    ];
	/**
	 * Checklist relationship
	 */
	public function checklist()
	{
		return $this->belongsTo('App\Models\Checklist');
	}
	/**
	 * Users relationship
	 */
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
	/**
	 * Facility-sdp relationship
	 */
	public function facilitySdp()
	{
		return $this->belongsTo('App\Models\FacilitySdp');
	}
	/**
	 * SurveyQuestions relationship
	 */
	public function questions()
	{
		return $this->hasMany('App\Models\SurveyQuestion');
	}
	/**
	 * htc-survey-pages relationship
	 */
	public function pages()
	{
		return $this->hasMany('App\Models\HtcSurveyPage');
	}
	/**
	* Calculation of positive percent[ (Total Number of Positive Results/Total Number of Specimens Tested)*100 ]
	*/
	public function positivePercent(){
		return round($this->positive*100/($this->positive+$this->negative+$this->indeterminate), 2);
	}
	/**
	* Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
	*/
	public function overallAgreement($kit){
		//    Initialize variables
        $total = 0;
        $invalid = 0;
        $reactiveOne = 0;
        $nonReactiveOne = 0;
        $reactiveTwo = 0;
        $nonReactiveTwo = 0;        
        /*pages*/
        $pages =  $this->pages->lists('id');
        //  Get pages with the screening question being answered by kit
        $refinedPages = [];
        $screen = Question::idById('screen');   //  Question whose response is either determine or khb
        // work in reverse to get pages
        $data = HtcSurveyPageData::where('answer', $kit)->lists('htc_survey_page_question_id');
        $refinedIds = HtcSurveyPageQuestion::whereIn('id', $data)->where('question_id', $screen)->lists('htc_survey_page_id');
        $refinedPages = array_intersect($pages, $refinedIds);
        /*htc survey page questions*/
        $quest = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $refinedPages);
        /*htc survey data*/
        //  Get questions to be used in the math
        $testOnePos = Question::idByName('Test-1 Total Positive');
        $testOneNeg = Question::idByName('Test-1 Total Negative');
        $testOneInv = Question::idByName('Test-1 Total Invalid');
        $testTwoPos = Question::idByName('Test-2 Total Positive');
        $testTwoNeg = Question::idByName('Test-2 Total Negative');
        $testTwoInv = Question::idByName('Test-2 Total Invalid');
        $totalTestOne = [$testOnePos, $testOneNeg, $testOneInv];
        $invalids = [$testOneInv, $testTwoInv];
        //  Math
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
	 * survey-question relationship
	 */
	public function sqs()
	{
		return $this->hasMany('App\Models\SurveyQuestion');
	}
    /**
     * Get register-start-dates for all pages of the survey
     */
    public function dates()
    {
    	$question_id = Question::idByName('Register Page Start Date');
    	$dates = $this->pages()
    					->join('htc_survey_page_questions', 'htc_survey_pages.id', '=', 'htc_survey_page_questions.htc_survey_page_id')
        				->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')
        				->where('question_id', $question_id)
        				->lists('answer');
        if(!count($dates)>0)
        {
        	$dates = [$this->date_submitted];
        }
        usort($dates, function($a, $b) {
		    $dateTimestamp1 = strtotime($a);
		    $dateTimestamp2 = strtotime($b);

		    return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
		});
        return json_encode(['min' => $dates[0], 'max' => $dates[count($dates)-1]]);
    }
    /**
     * Function to calculate level
     */
    public function level()
    {
        //  Define variables for use
        $counter = 0;
        $total_checklist_points = $this->checklist->sections->sum('total_points');
        $unwanted = array(Question::idById('providersenrolled'), Question::idById('correctiveactionproviders')); //  do not contribute to total score
        $notapplicable = Question::idById('dbsapply');  //  dbsapply will reduce total points to 65 if corresponding answer = 0
        //  Begin processing
        $reductions = 0;
        $calculated_points = 0.00;
        $percentage = 0.00;
        $sqtns = $this->sqs()->whereNotIn('question_id', $unwanted)    //  remove non-contributive questions
                              ->join('survey_data', 'survey_questions.id', '=', 'survey_data.survey_question_id')
                              ->whereIn('survey_data.answer', Answer::lists('score'));
        $calculated_points = $sqtns->whereIn('question_id', array_unique(DB::table('question_responses')->lists('question_id')))->sum('answer');    
        if($sq = SurveyQuestion::where('survey_id', $this->id)->where('question_id', $notapplicable)->first())
        {
            if($sq->sd->answer == '0')
                $reductions++;
        }
            
        if($reductions>0)
            $percentage = round(($calculated_points*100)/($total_checklist_points-5), 2);
        else
            $percentage = round(($calculated_points*100)/$total_checklist_points, 2);
        return $percentage;
    }
    /**
    * Calculation of positive agreement[ (Total Reactive Results from Test 2/Total Reactive Results from Test 1)*100 ]
    */
    public function positiveAgreement($kit)
    {
        //  Initialize counts
        $testOne = 0;
        $testTwo = 0;       
        /*pages*/
        $pages =  $this->pages->lists('id');
        //  Get pages with the screening question being answered by kit
        $refinedPages = [];
        $screen = Question::idById('screen');   //  Question whose response is either determine or khb
        // work in reverse to get pages
        $data = HtcSurveyPageData::where('answer', $kit)->lists('htc_survey_page_question_id');
        $refinedIds = HtcSurveyPageQuestion::whereIn('id', $data)->where('question_id', $screen)->lists('htc_survey_page_id');
        $refinedPages = array_intersect($pages, $refinedIds);
        /*htc survey page questions*/
        $quest = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $refinedPages);
        //  Declare questions to be used in calculation of both values
        $posOne = Question::idByName('Test-1 Total Positive');
        $posTwo = Question::idByName('Test-2 Total Positive');
        //  For each of the pages, get to data and add the given values
        $one = clone $quest; $two = clone $quest;
        $testOne = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $one->where('question_id', $posOne)->lists('id'))->sum('answer');
        $testTwo = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $two->where('question_id', $posTwo)->lists('id'))->sum('answer');
        return $testOne>0?round((int)$testTwo*100/(int)$testOne, 2):0.00;
    }
}
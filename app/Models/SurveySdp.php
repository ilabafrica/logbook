<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class SurveySdp extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'survey_sdps';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'survey_id',
        'sdp_id',
        'comment',
    ];
	/**
     * Survey relationship
     */
    public function survey()
    {
       return $this->belongsTo('App\Models\Survey');
    }
    /**
     * Sdp relationship
     */
    public function sdp()
    {
       return $this->belongsTo('App\Models\Sdp');
    }
    /**
     * pages relationship
     */
    public function pages()
    {
        return $this->hasMany('App\Models\HtcSurveyPage');
    }
    /**
     * survey-question relationship
     */
    public function sqs()
    {
        return $this->hasMany('App\Models\SurveyQuestion');
    }
    /**
     * survey-question relationship given the question id
     */
    public function sq($id)
    {
        return $this->sqs->where('question_id', $id)->first();
    }
    /**
    * Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
    */
    public function overallAgreement()
    {
        //  Initialize variables
        $total = 0;
        $invalid = 0;
        $reactiveOne = 0;
        $nonReactiveOne = 0;
        $reactiveTwo = 0;
        $nonReactiveTwo = 0;        
        /*htc survey page questions*/
        $quest = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $this->pages->lists('id'));
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
        return ($total - $invalid)>0?round(($reactiveTwo+$nonReactiveOne) * 100 / ($total-$invalid), 2):0;
    }
    /**
    *
    *   Function to eager load data for use in positive/overall agreement
    *
    */
    public function eagerPages($kit, $sdp = null, $facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
    {
        //  Check dates
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
        $screen = Question::idById('screen');   //  Question whose response is either determine or khb
        //  Get pages whose screening test is as given(khb/determine)
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
            if($subCounty || $facility)
            {
                if($facility)
                {
                    $q->where('facility_id', $facility);
                }
                else
                {
                    $surveys = $surveys->whereHas('facility', function($q) use($subCounty){
                        $q->where('sub_county_id', $subCounty);
                    });
                }
            }
            else
            {
                $surveys = $surveys->whereHas('facility', function($q) use($county){
                    $q->whereHas('subCounty', function($q) use ($county){
                        $q->where('county_id', $county);
                    });
                });
            }
        }
        $surveys = $surveys->lists('surveys.id');
        /*survey sdps*/
        $ssdps = SurveySdp::whereIn('survey_id', $surveys);
        if($sdp)
        {
            $ssdps = $ssdps->where('sdp_id', $sdp);
        }
        $ssdps = $ssdps->lists('id');
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
}
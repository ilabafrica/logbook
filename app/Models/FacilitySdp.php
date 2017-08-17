<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface
use DB;

class FacilitySdp extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'facility_sdps';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];

	/**
     * Facility relationship
     */
    public function facility()
    {
       return $this->belongsTo('App\Models\Facility');
    }
    /**
     * Sdp relationship
     */
    public function sdp()
    {
       return $this->belongsTo('App\Models\Sdp');
    }
    /**
     * surveys relationship
     */
    public function surveys()
    {
       return $this->hasMany('App\Models\Survey');
    }
    /**
    * Function to split given string to get sdp and comment
    */
    public static function splitSdp($facility, $id)
    {
        $sdpName = '';
        $tier_id = null;
        if(stripos($id, '-') !==FALSE)
        {
            $id = explode('-', $id);
            $sdpName = $id[0];
            if(trim($id[1])!='')
                $tier_id = $id[1];
        }
        else
            $sdpName = $id;
        $sdp_id = Sdp::idByName($sdpName);
        $tier_id = Tier::find(trim($tier_id));
        return FacilitySdp::where('facility_id', $facility)->where('sdp_id', $sdp_id)->where('sdp_tier_id', $tier_id)->first();
    }
    /**
    * Function to split given string to get sdp and comment
    */
    public static function cojoin($id)
    {
        $cojoined = FacilitySdp::find($id);
        $cojoined->sdp_tier_id?$fsdp=Sdp::find($cojoined->sdp_id)->name.' - '.Tier::find($cojoined->sdp_tier_id)->name:$fsdp=Sdp::find($cojoined->sdp_id)->name;
        return $fsdp;
    }
    /**
     * Function to calculate percentage of submissions in each level and sdp for spirt
     */
    public function level($lvl, $from, $to, $theDate = NULL)
    {
        $level = Level::find($lvl);
        $chkId = Checklist::idByName('SPI-RT Checklist');
        //  Define variables for use
        $counter = 0;
        $total_checklist_points = Checklist::find(Checklist::idByName('SPI-RT Checklist'))->sections->sum('total_points');
        $unwanted = array(Question::idById('providersenrolled'), Question::idById('correctiveactionproviders')); //  do not contribute to total score
        $notapplicable = Question::idById('dbsapply');  //  dbsapply will reduce total points to 65 if corresponding answer = 0
        $surveys = $this->surveys()->where('checklist_id', $chkId);
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
        if(($percentage>=$level->range_lower) && ($percentage<$level->range_upper+1) && ($percentage!=0))
            return $percentage;
        else
            return 0.00;
    }
    /**
    * Calculation of positive percent[ (Total Number of Positive Results/Total Number of Specimens Tested)*100 ] - Aggregated
    */
    public function positivePercent($sdp = NULL, $facility = NULL, $sub_county = NULL, $county = NULL, $year = 0, $month = 0, $date = 0, $from = NULL, $to = NULL)
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
        $checklist = Checklist::idByName('HTC Lab Register (MOH 362)');
        $srvys = $this->surveys()->where('checklist_id', $checklist);
        if (strlen($theDate)>0 || ($from && $to))
        {
            if($from && $to)
                $srvys = $srvys->whereBetween('data_month', [$from, $to]);
            else
                $srvys = $srvys->where('data_month', 'LIKE', $theDate."%");
        }
        $srvys = $srvys->lists('id');
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
        $total = $this->eagerLoad($srvys, $totals);
        $positive = $this->eagerLoad($srvys, $positives);
        return $total>0?round((int)$positive*100/(int)$total, 2):0;
    }
    /**
    * Calculation of positive agreement[ (Total Reactive Results from Test 2/Total Reactive Results from Test 1)*100 ]
    */
    public function positiveAgreement($kit, $sdp = NULL, $facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
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
        //  Initialize counts
        $testOne = 0;
        $testTwo = 0;
        $screen = Question::idById('screen');   //  Question whose response is either determine or khb       
        /*pages*/
        $checklist = Checklist::idByName('HTC Lab Register (MOH 362)');
        $srvys = $this->surveys()->where('checklist_id', $checklist);
        if (strlen($theDate)>0 || ($from && $to))
        {
            if($from && $to)
                $srvys = $srvys->whereBetween('data_month', [$from, $to]);
            else
                $srvys = $srvys->where('data_month', 'LIKE', $theDate."%");
        }
        $srvys = $srvys->lists('id');
        $pages =  HtcSurveyPage::whereIn('survey_id', $srvys)->lists('id');
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
        return $testOne>0?(round((int)$testTwo*100/(int)$testOne, 2)>100?100:round((int)$testTwo*100/(int)$testOne, 2)):0.00;
    }
    /**
    * Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
    */
    public function overallAgreement($kit, $year = 0, $month = 0, $date = 0, $from = null, $to = null)
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
        //  Initialize variables
        $total = 0;
        $invalid = 0;
        $reactiveOne = 0;
        $nonReactiveOne = 0;
        $reactiveTwo = 0;
        $nonReactiveTwo = 0;        
        /*pages*/
        $checklist = Checklist::idByName('HTC Lab Register (MOH 362)');
        $srvys = $this->surveys()->where('checklist_id', $checklist);
        if (strlen($theDate)>0 || ($from && $to))
        {
            if($from && $to)
                $srvys = $srvys->whereBetween('data_month', [$from, $to]);
            else
                $srvys = $srvys->where('data_month', 'LIKE', $theDate."%");
        }
        $srvys = $srvys->lists('id');
        if(!empty($srvys))
        {
            $pages =  HtcSurveyPage::whereIn('survey_id', $srvys)->lists('id');
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
        }
        $absReactive = abs($reactiveTwo-$reactiveOne);
        $absNonReactive = abs($nonReactiveTwo-$nonReactiveOne);
        return ($total - $invalid)>0?round(($reactiveTwo+$nonReactiveOne) * 100 / ($total-$invalid), 2):0;
    }
    /**
    *   Function to eager-load questions for use in calculating other derivatives
    */
    public function eagerLoad($surveys, $qstns)
    {
        $pages = HtcSurveyPage::whereIn('survey_id', $surveys)->lists('id');
        $questions = HtcSurveyPageQuestion::whereIn('htc_survey_page_id', $pages)->whereIn('question_id', $qstns)->lists('id');
        $data = HtcSurveyPageData::whereIn('htc_survey_page_question_id', $questions)->sum('answer');
        return $data;
    }
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface
use DB;

class Level extends Model implements Revisionable{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'levels';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'description',
        'range_lower',
        'range_upper',
        'user_id',
    ];
    /**
     * Function to calculate percentage of submissions in each level and sdp
     */
    public function level($checklist, $county = NULL, $sub_county = NULL, $site = NULL, $sdp, $from = NULL, $to = NULL)
    {
        //  Get data to be used
        $values = SurveySdp::join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
                            ->where('checklist_id', $checklist)
                            ->where('sdp_id', $sdp);
                            if($from && $to)
                            {
                                $values = $values->whereBetween('date_submitted', [$from, $to]);
                            }
                            if($county || $sub_county || $site)
                            {
                                if($sub_county || $site)
                                {
                                    if($site)
                                    {
                                        $values = $values->where('facility_id', $site);
                                    }
                                    else
                                    {
                                        $values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
                                                         ->where('sub_county_id', $sub_county);
                                    }
                                }
                                else
                                {
                                    $values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
                                                     ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
                                                     ->where('county_id', $county);
                                }
                            }
                            $values = $values->get(array('survey_sdps.*'));
        //  Define variables for use
        $counter = 0;
        $total_counts = count($values);
        $total_checklist_points = Checklist::find($checklist)->sections->sum('total_points');
        $unwanted = array(Question::idById('providersenrolled'), Question::idById('correctiveactionproviders')); //  do not contribute to total score
        $notapplicable = Question::idById('dbsapply');  //  dbsapply will reduce total points to 65 if corresponding answer = 0
        //  Begin processing
        foreach ($values as $key => $value)
        {
            $reductions = 0;
            $calculated_points = 0.00;
            $percentage = 0.00;
            $sqtns = $value->sqs()->whereNotIn('question_id', $unwanted)    //  remove non-contributive questions
                                  ->join('survey_data', 'survey_questions.id', '=', 'survey_data.survey_question_id')
                                  ->whereIn('survey_data.answer', Answer::lists('score'));
            $calculated_points = $sqtns->sum('answer');    
            $reductions = $sqtns->where('question_id', $notapplicable)
                                ->where('answer', '0')
                                ->count();
            if($reductions>0)
                $percentage = round(($calculated_points*100)/($total_checklist_points-5), 2);
            else
                $percentage = round(($calculated_points*100)/$total_checklist_points, 2);
            //  Check and increment counter
            if(($percentage>$this->range_lower) && ($percentage<=$this->range_upper) || (($this->range_lower==0.00) && ($percentage==$this->range_lower)))
                $counter++;
        }
        return $total_counts > 0?round($counter*100/$total_counts, 2):0.00;
    }
     /**
     * Function to calculate percentage of submissions in each level and sdp for spirt
     */
    public function spirtLevel($checklist, $ssdp)
    {
        //  Define variables for use
        $counter = 0;
        $total_checklist_points = Checklist::find($checklist)->sections->sum('total_points');
        $unwanted = array(Question::idById('providersenrolled'), Question::idById('correctiveactionproviders')); //  do not contribute to total score
        $notapplicable = Question::idById('dbsapply');  //  dbsapply will reduce total points to 65 if corresponding answer = 0
        //  Begin processing
        $reductions = 0;
        $calculated_points = 0.00;
        $percentage = 0.00;
        $sqtns = $ssdp->sqs()->whereNotIn('question_id', $unwanted)    //  remove non-contributive questions
                              ->join('survey_data', 'survey_questions.id', '=', 'survey_data.survey_question_id')
                              ->whereIn('survey_data.answer', Answer::lists('score'));
        $calculated_points = $sqtns->whereIn('question_id', array_unique(DB::table('question_responses')->lists('question_id')))->sum('answer');    
        if($sq = SurveyQuestion::where('survey_sdp_id', $ssdp->id)->where('question_id', $notapplicable)->first())
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
}

<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Section extends Model implements Revisionable {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sections';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];

	/**
	 * Questions relationship
	 */
	public function questions()
	{
		return $this->hasMany('App\Models\Question');
	}
	/**
	 * Checklist relationship
	 */
	public function checklist()
	{
		return $this->belongsTo('App\Models\Checklist');
	}
	/**
	 * Check if section has scorable questions
	 */
	public function isScorable()
	{
		$array = array();
		foreach ($this->questions as $question) {
			$answers = array();
			if($question->answers->count()>0)
			{	
				foreach ($question->answers as $response) 
				{
					if($response->score>0)
						array_push($answers, $response->id);
				}
			}
			if(count($answers)>0)
				array_push($array, $question->id);
		}
		if(count($array)>0)
			return true;
		else
			return false;
	}
	/**
	 * Function to calculate scores per section
	 */
	public function spider($sdp = NULL, $site = NULL, $sub_county = NULL, $county = NULL, $from = NULL, $to = NULL, $year = 0, $month = 0, $date = 0)
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
		$checklist = Checklist::idByName('SPI-RT Checklist');
        //  Check dates
        $fsdps = Checklist::find($checklist)->fsdps($checklist, $county, $sub_county, $site, $sdp, $from, $to, $year, $month, $date)->lists('facility_sdp_id');
        $surveys = Survey::where('checklist_id', $checklist)->whereIn('facility_sdp_id', $fsdps);
        if (strlen($theDate)>0 || ($from && $to))
        {
            if($from && $to)
                $surveys = $surveys->whereBetween('date_submitted', [$from, $to]);
            else
            	$surveys = $surveys->where('date_submitted', 'LIKE', $theDate."%");
        }
        $surveys = $surveys->lists('id');
        //  Define variables for use
        $counter = 0;
        $total_counts = count($fsdps);
        $total_checklist_points = Checklist::find($checklist)->sections->sum('total_points');
        $unwanted = array(Question::idById('providersenrolled'), Question::idById('correctiveactionproviders')); //  do not contribute to total score
        $notapplicable = Question::idById('dbsapply');  //  dbsapply will reduce total points to 65 if corresponding answer = 0
        $percentage = 0.00;
        //  Begin processing
        $questions = SurveyQuestion::whereIn('survey_id', $surveys)->whereNotIn('question_id', $unwanted)->whereIn('question_id', $this->questions->lists('id'))->lists('id');
        $dbs = SurveyQuestion::whereIn('survey_id', $surveys)->where('question_id', $notapplicable)->lists('id');
        $na = SurveyData::whereIn('survey_question_id', $dbs)->where('answer', '0')->count();
        $calculated_points = SurveyData::whereIn('survey_question_id', $questions)->whereIn('answer', Answer::lists('score'))->sum('answer');
        return $total_counts>0?round(($calculated_points*100)/($this->total_points*$total_counts), 2):$percentage;
		//	End optimization
	}
	/**
	 * Function to calculate the snapshot given section
	 */
	public function snapshot($county = null, $sub_county = null, $site = null, $sdp = null, $from = NULL, $to = NULL)
	{
       		//	Initialize variables
		$counter = 0;
		$checklist = Checklist::idByName('M & E Checklist');
		$fsdps = $this->fsdps($checklist, $county, $sub_county, $site, $sdp, $from, $to);
        $total_counts = count($fsdps);
        $surveys = $fsdps->lists('id');
        $sq = SurveyQuestion::whereIn('survey_id', $surveys)->whereIn('question_id', $this->questions->lists('id'))->lists('id');
        $calculated_points = SurveyData::whereIn('survey_question_id', $sq)
        							->whereIn('survey_data.answer', Answer::lists('score'))
        							->sum('answer');
        return $total_counts>0?round(($calculated_points*100)/($this->total_points*$total_counts), 2):0.00;
        //  End optimization
    }
	/**
	 * Function to return color code given the percent value
	 */
	public static function color($percent)
	{
		$class = '';
		if($percent >= 0 && $percent <25)
			$class = 'does-not-exist';
		else if($percent >=25 && $percent <50)
			$class = 'in-development';
		else if($percent >=50 && $percent <75)
			$class = 'being-implemented';
		else if($percent >=75 && $percent <100)
			$class = 'completed';
		return $class;
	}
	/**
	 * Function to calculate number of specific responses
	 */
	public function column($option, $county = null, $sub_county = null, $site = null, $sdp = null, $from = NULL, $to = NULL)
	{
        //	Initialize variables
        $checklist = Checklist::idByName('M & E Checklist');
		$fsdps = $this->fsdps($checklist, $county, $sub_county, $site, $sdp, $from, $to);
        $response = Answer::find(Answer::idByName($option))->score;
        $counter = $this->questions->where('score', '!=', 0)->count();
        $total_counts = count($fsdps);
        $surveys = $fsdps->lists('id');
        $sq = SurveyQuestion::whereIn('survey_id', $surveys)->whereIn('question_id', $this->questions->lists('id'))->lists('id');
        $calculated_counts = SurveyData::whereIn('survey_question_id', $sq)
                                  	->whereIn('survey_data.answer', Answer::lists('score'))
                                  	->where('answer', $response)
                                  	->count();
        return $total_counts>0?round(($calculated_counts*100)/($total_counts*$counter), 2):0.00;
	}
	/**
	 * Function to calculate percentage based on quarters
	 */
	public function quarter($period)
	{
		$checklist = $this->checklist;
		$survey_sdp_ids = NULL;
		$counter = 0;
		if($period === 'Baseline')
		{
			$survey_sdp_ids = SpirtInfo::lists('survey_sdp_id');
			$counter = count($survey_sdp_ids);
		}
		else
		{
			$start_date = NULL;
			$end_date = NULL;
			if(substr($period, 8) == '1')
			{
				$start_date = date('Y-10-01');
				$end_date = date('Y-12-31');
			}
			else if(substr($period, 8) === '2')
			{
				$start_date = date('Y-01-01');
				$end_date = date('Y-03-31');	
			}
			else if(substr($period, 8) === '3')
			{
				$start_date = date('Y-04-01');
				$end_date = date('Y-06-30');	
			}
			else if(substr($period, 8) === '3')
			{
				$start_date = date('Y-07-01');
				$end_date = date('Y-09-30');	
			}
			$survey_sdp_ids = SpirtInfo::join('survey_sdps', 'survey_sdps.id', '=', 'survey_spirt_info.survey_sdp_id')
										->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
										->whereBetween('date_started', [$start_date, $end_date])
										->lists('survey_spirt_info.survey_sdp_id');
			$counter = count($survey_sdp_ids);
		}
		if($this->isScorable())
		{
			$total = 0.0;		
			foreach ($this->questions as $question)
			{
				if($question->answers->count()>0)
				{
					foreach ($question->sqs as $sq) 
					{
						if(in_array($sq->survey_sdp_id, $survey_sdp_ids))
							$total+=$sq->sd->answer;
					}
				}
			}
			return $counter!=0?round(($total*100)/($this->total_points*$counter), 2):0.00;
		}
	}
	/**
	* Return Section ID given the name
	* @param $name the name of the section
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$section = Section::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $section->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The section ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
    /**
     * Function to calculate percentage of sites in each domain/pillar per level
     */
    public function level($checklist, $response, $county = NULL, $sub_county = NULL, $site = NULL, $sdp = NULL, $from = NULL, $to = NULL)
    {
        $checklist = Checklist::idByName('M & E Checklist');
        //  Get answer object from $response
        $level = Answer::find(Answer::idByName($response));
        //  Get data to be used
        $fsdps = Checklist::find($checklist)->fsdps($checklist, $county, $sub_county, $site, $sdp, $from, $to);
        //  Define variables for use
        $counter = 0;
        $total_counts = $fsdps->count();
        //  Begin processing
        foreach ($fsdps->get() as $key => $value)
        {
            $calculated_points = 0.00;
            $percentage = 0.00;
            $sqtns = $value->sqs()->whereIn('question_id', $this->questions->lists('id'))    //  Get questions belonging to the section
                                  ->join('survey_data', 'survey_questions.id', '=', 'survey_data.survey_question_id')
                                  ->whereIn('survey_data.answer', array_filter(Answer::lists('score')));
            $calculated_points = $sqtns->sum('answer');
            $percentage = round(($calculated_points*100)/$this->total_points, 2);
            //  Check and increment counter
            if(($percentage>$level->range_lower) && ($percentage<=$level->range_upper) || (($level->range_lower==0.00) && ($percentage==$level->range_lower)))
            {
                $counter++;
            }
        }
        return $counter;
    }
    /**
     * Function to load fsdps given the different variables
     */
    public function fsdps($checklist, $county = null, $sub_county = null, $site = null, $sdp = null, $from = NULL, $to = NULL, $year = 0, $month = 0, $date = 0)
    {
        $fsdps = [];
        $values = Survey::where('checklist_id', $checklist);
        if($from && $to)
        {
            $values = $values->whereBetween('date_submitted', [$from, $to]);
        }
        if($county || $sub_county || $site || $sdp)
        {
            if($sub_county || $site || $sdp)
            {
                if($site || $sdp)
                {                                
                    if($sdp)
                    {
                        $fsdps = $sdp;
                    }
                    else
                    {
                        $fsdps = Facility::find($site)->facilitySdp->lists('id');
                    }
                }
                else
                {
                    foreach (SubCounty::find($sub_county)->facilities as $facility)
                    {
                        foreach ($facility->facilitySdp as $fsdp)
                        {
                            array_push($fsdps, $fsdp->id);
                        }
                    }
                }
            }
            else
            {
                foreach(County::find($county)->subCounties as $subCounty)
                {
                    foreach ($subCounty->facilities as $facility)
                    {
                        foreach ($facility->facilitySdp as $fsdp)
                        {
                            array_push($fsdps, $fsdp->id);
                        }
                    }
                }
            }
        }
        if(count($fsdps)>0)
            $values = $values->whereIn('facility_sdp_id', $fsdps);
        return $values->get();
    }
}
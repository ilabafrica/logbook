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
        'name',
        'description',
        'user_id',
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
	public function spider()
	{
		//dd($this->label);
		$points = 0.0;
		$array = array();
		foreach ($this->questions as $question) {
			if($question->answers->count()>0)
				array_push($array, $question);
		}
		//dd($questions);
		foreach ($array as $question) {
			$points+=SurveyQuestion::where('question_id', $question->id)->first()->ss->score;
		}
		return $points;
	}
	/**
	 * Function to calculate the snapshot given section
	 */
	public function snapshot()
	{
		//	Initialize variables
		$count = Checklist::find(Checklist::idByName('M & E Checklist'))->surveys->count();
		if($this->isScorable())
		{
			$total = 0.0;		
			foreach ($this->questions as $question)
			{
				if($question->answers->count()>0)
				{
					foreach ($question->sqs as $sq) 
					{
						$total+=$sq->ss->score;
					}
				}
			}
			return $count!=0?round(($total*100)/($count*$this->total_points), 2):0.00;
		}
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
	public function column()
	{
		//	Initialize variables
		$total = 0;
		foreach ($this->questions as $question) {
			$questions = array();
			$sqs = SurveyQuestion::where('question_id', $question->id)->get();
			foreach ($sqs as $sq) {
				if($sq->sd->answer)
					$total++;
			}
		}
		return $total;
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
							$total+=$sq->ss->score;
					}
				}
			}
			return $counter!=0?round(($total*100)/($this->total_points*$counter), 2):0.00;
		}
	}
}
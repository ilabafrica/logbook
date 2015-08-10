<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sections';

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
			return round(($total*100)/($count*$this->total_points), 2);
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
		$survey_ids = NULL;
		if($period === 'Baseline')
			$survey_ids = SpirtInfo::lists('survey_id');
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
			$survey_ids = SpirtInfo::/*whereBetween('created_at', [$start_date, $end_date])->*/lists('survey_id');
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
						if(in_array($sq->survey_id, $survey_ids))
							$total+=$sq->ss->score;
					}
				}
			}
			return round(($total*100)/($this->total_points), 2);
		}
	}
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface
use Lang;

class Answer extends Model implements Revisionable {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'responses';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];
	/**
	* Responses for questions
	*/
	const NO = 0;
	const YES = 1;	
	const PARTIAL = 0.5;
	/**
	* Return response ID given the name
	* @param $name the name of the response
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$response = Answer::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $response->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The response ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	 * Function to calculate number of specific responses
	 */
	public function column($id, $county = null, $sub_county = null, $site = null, $sdp =null, $from = NULL, $to = NULL)
	{
		//	Initialize variables
		$total = 0;
		$section = Section::find($id);
		$checklist = $section->checklist;
		$surveys = $checklist->fsdps($checklist->id, $county, $sub_county, $site, $sdp, $from, $to)->lists('id');
		$questions = SurveyQuestion::whereIn('survey_id', $surveys)->whereIn('question_id', $section->questions->lists('id'))->lists('id');
		$counter = SurveyData::whereIn('survey_question_id', $questions)->where('answer', $this->score)->count();
		return $counter;
	}
	/**
	 * Function to return range given the name
	 */
	public static function range($id)
	{
		$range = '';
		if($id === 'Does Not Exist')
			$range = Lang::choice('messages.dne-range', 1);
		else if($id === 'In Development')
			$range = Lang::choice('messages.id-range', 1);
		else if($id === 'Being Implemented')
			$range = Lang::choice('messages.bi-range', 1);
		else if($id === 'Completed')
			$range = Lang::choice('messages.c-range', 1);
		return $range;
	}
	/**
	* Return response name given the score
	* @param $score the score of the response
	*/
	public static function nameByScore($checklist = null, $score=NULL)
	{
		if($score!=NULL){
			try 
			{
				$response = Answer::where('score', $score);
				if($checklist)
					$response = $response->orderBy('name', 'asc');
				else
					$response = $response->orderBy('name', 'desc');
				$response = $response->firstOrFail();
				return $response->name;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The score ` $score ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	* Return response answer given value
	* @param $score the score of the response
	*/
	public static function decode($type, $i)
	{
		$answer = null;
		if($type == Question::CHOICE)
		{
			if($i == '0')
				$answer = 'No';
			else if($i == '1')
				$answer = 'Yes';
			else if(in_array($i, Answer::lists('description')))
				$answer = Answer::where('description', $i)->first()->name;
			else
				$answer = $i;
		}
		else
			$answer = $i;
		return $answer;
	}
}

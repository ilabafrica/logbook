<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;

class Answer extends Model {

	protected $table = 'responses';
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
	public function column($id)
	{
		//	Initialize variables
		$total = 0;
		foreach (Section::find($id)->questions as $question) {
			$questions = array();
			$sqs = SurveyQuestion::where('question_id', $question->id)->get();
			foreach ($sqs as $sq) {
				if($sq->sd->answer == $this->name)
					$total++;
			}
		}
		return $total;
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
}

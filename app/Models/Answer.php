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
        'name',
        'description',
        'score',
        'range_lower',
        'range_upper',
        'user_id',
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
		foreach (Section::find($id)->questions as $question)
		{
			$questions = array();
			$values=SurveyQuestion::where('question_id', $question->id)
									->join('survey_sdps', 'survey_sdps.id', '=', 'survey_questions.survey_sdp_id')
									->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id');
									if($from && $to)
									{
										$values = $values->whereBetween('date_submitted', [$from, $to]);
									}
									if($county || $sub_county || $site ||$sdp)
									{
										if($sub_county || $site ||$sdp)
										{
											if($site ||$sdp)
											{
												if(isset($sdp))
												{
													$values = $values->where('sdp_id', $sdp);
												}
												else
												{ 
													$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
																	 ->where('facility_id', $site);	
												}
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
									
			$values = $values->get(array('survey_questions.*'));
			foreach ($values as $key => $value)
			{
				$sq = SurveyQuestion::find($value->id);
				if($sq->sd)
				{
					if($sq->sd->answer == $this->score)
					$total++;
				}
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
}

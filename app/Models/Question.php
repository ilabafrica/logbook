<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface
use Lang;


class Question extends Model implements Revisionable  {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'questions';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'description',
        'question_type',
        'user_id',
    ];
	//	Constants for type of field
	const CHOICE = 0;
	const DATE = 1;
	const FIELD = 2;
	const TEXTAREA = 3;
	const SELECT = 4;
	const MULTICHOICE = 5;
	//	Constants for whether field is required
	const REQUIRED = 1;
	
	/**
	 * Section relationship
	 */
	public function section()
	{
		return $this->belongsTo('App\Models\Section');
	}
	/**
	 * Answers relationship
	 */
	public function answers()
	{
	  return $this->belongsToMany('App\Models\Answer', 'question_responses', 'question_id', 'response_id');
	}
	/**
	 * Set possible responses where applicable
	 */
	public function setAnswers($field){

		$fieldAdded = array();
		$questionId = 0;	

		if(is_array($field)){
			foreach ($field as $key => $value) {
				$fieldAdded[] = array(
					'question_id' => (int)$this->id,
					'response_id' => (int)$value,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
					);
				$questionId = (int)$this->id;
			}

		}
		// Delete existing parent-child mappings
		DB::table('question_responses')->where('question_id', '=', $questionId)->delete();

		// Add the new mapping
		DB::table('question_responses')->insert($fieldAdded);
	}
	/**
	* Decode question type
	*/
	public function q_type()
	{
		$type = $this->question_type;
		if($type == Question::CHOICE)
			return 'Choice';
		else if($type == Question::DATE)
			return 'Date';
		else if($type == Question::FIELD)
			return 'Field';
		else if($type == Question::TEXTAREA)
			return 'Free Text';
		else if($type == Question::SELECT)
			return 'Select List';
		else if($type == Question::MULTICHOICE)
			return 'Checkbox';
	}
	/**
	* Return Question ID given the name
	* @param $name the name of the user
	*/
	public static function idByName($name=NULL, $checklist = NULL)
	{
		if($name!=NULL){
			try 
			{
				$question = Question::select('questions.*');
				if($checklist != NULL){
					$question = $question->join('sections', 'questions.section_id', '=', 'sections.id')
										 ->where('sections.checklist_id', $checklist);
				}
				$question = $question->where('questions.name', $name)->orderBy('questions.id', 'asc')->firstOrFail();
				return $question->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The question ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	* Return response of a given question
	* @param $id the id of the survey
	*/
	public function sq($id)
	{
		return SurveyQuestion::where('survey_id', $id)
						 ->where('question_id', $this->id)
						 ->first();
	}
	/**
	 * Count number of responses of the type for the question
	 */
	public function responses($answer)
	{
		return SurveyData::where('question_id', $this->id)
						 ->where('answer', $answer)
						 ->count();
	}
	/**
	* Return Survey-Questions of the given question
	* @param $id the id of the survey
	*/
	public function sqs()
	{
		return $this->hasMany('App\Models\SurveyQuestion');
	}
	/**
	* Return Question ID given the identifier
	* @param $name the identifier of the question
	*/
	public static function idById($id=NULL)
	{
		if($id!=NULL){
			try 
			{
				$question = Question::where('identifier', $id)->orderBy('name', 'asc')->firstOrFail();
				return $question->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The question with identifier ` $id ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	 * Check if question has scorable answers
	 */
	public function isScorable()
	{
		$scorables = array();
		foreach ($this->answers as $response)
		{
			if($response->score>0)
				array_push($scorables, $response->id);
		}
		if(count($scorables)>0)
			return true;
		else
			return false;
	}
	/**
	* Return Question name given the identifier
	* @param $id the identifier of the question
	*/
	public static function nameById($id=NULL)
	{
		if($id!=NULL)
		{
			try 
			{
				$question = Question::where('id', $id)->orderBy('name', 'asc')->firstOrFail();
				return $question->name;
			}
			catch (ModelNotFoundException $e) 
			{
				Log::error("The question with id ` $id ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	/**
	 * counts sites for m & e domain responses
	 */
	public function breakdown($option, $sdp=null, $site=null, $sub_county=null, $county=null, $from=null, $to=null)
	{
		$response = Answer::find(Answer::idByName($option))->score;
    	$counter = SurveySdp::join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
    			 			->join('survey_questions', 'survey_sdps.id', '=', 'survey_questions.survey_sdp_id')
    			 			->join('survey_data', 'survey_questions.id', '=', 'survey_data.survey_question_id')
    			 			->where('question_id', $this->id)
    			 			->where('answer', $response);
    			 			if($from && $to)
							{
								$counter = $counter->whereBetween('date_submitted', [$from, $to]);
							}
							if($county || $sub_county || $site)
							{
								if($sub_county || $site)
								{
									if(isset($site))
									{
										$counter = $counter->where('facility_id', $site);
									}
									else
									{
										$counter = $counter->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
												 		 ->where('sub_county_id', $sub_county);
									}
								}
								else
								{
									$counter = $counter->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
													 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
													 ->where('county_id', $county);
								}
							}
    			 			$counter = $counter->count();
    	return $counter;
	}
	/**
	 * Function to calculate percentage of response in domain
	 */
	public function domain($array, $answer, $county = NULL, $sub_county = NULL, $site = NULL, $sdp = NULL, $from = NULL, $to = NULL)
	{
		//	Get counts for each question per domain
		$counter = count($array);
		$percentage = 0.00;
		$perAnswer = $this->breakdown($answer, null, $site, $sub_county, $county, $from, $to);
		$overallCount = 0;
		foreach ($array as $option)
		{
			$overallCount+=$this->breakdown($option, null, $site, $sub_county, $county, $from, $to);
		}
		return round($perAnswer*100/$overallCount, 2);
	}
}
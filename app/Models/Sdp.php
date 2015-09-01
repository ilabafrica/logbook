<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Sdp extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sdps';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'description',
    ];

	/**
	 * Survey relationship
	 */
	public function surveys()
	{
		return $this->hasMany('App\Models\SurveySdp');
	}
	/**
	* Return Sdp ID given the name
	* @param $name the name of the user
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$sdp = Sdp::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $sdp->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The sdp ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	* Calculation of positive percent[ (Total Number of Positive Results/Total Number of Specimens Tested)*100 ] - Aggregated
	*/
	public function positivePercent($checklist = NULL, $from = NULL, $to = NULL, $testTypeID = NULL, $name = NULL, $surveys = NULL)
	{
		//	Initialize counts
		$positive = 0;
		$total = 0;
		//	Get sdp surveys
		$questions = array();
		foreach ($this->surveys as $survey)
		{
			foreach ($survey->pages as $page)
			{
				foreach ($page->questions as $question)
				{
					array_push($questions, Question::find($question->question_id)->name);
				}
			}
		}
		$questions = array_unique($questions);
		//dd($questions);
		//	Declare questions to be used in calculation of both positive and total values
		$qstns = array('Test-1 Total Positive', 'Test-1 Total Negative', 'Test-2 Total Positive', 'Test-3 Total Positive');
		//	Get the counts
		foreach ($qstns as $qstn) {
			$question = Question::idByName($qstn);
			$values = HtcSurveyPageQuestion::where('question_id', $question)->lists('id');
			foreach ($values as $key => $value) 
			{
				if(substr_count($qstn, 'Test-1')>0)
					$total+=HtcSurveyPageQuestion::find($value)->data->answer;
				else
					$positive+=HtcSurveyPageQuestion::find($value)->data->answer;
			}			
		}
		return $total>0?round((int)$positive*100/(int)$total, 2):0;
	}
	/**
	* Calculation of positive agreement[ (Total Reactive Results from Test 2/Total Reactive Results from Test 1)*100 ]
	*/
	public function positiveAgreement()
	{
		//	Initialize counts
		$testOne = 0;
		$testTwo = 0;
		//	Declare questions to be used in calculation of both values
		$qstns = array('Test-1 Total Positive', 'Test-2 Total Positive');
		//	Get sdp surveys
		$surveys = $this->surveys->lists('id');
		//	Calculation
		foreach ($qstns as $qstn) {
			$question = Question::idByName($qstn);
			$value = SurveyQuestion::where('question_id', $question)
								   ->whereIn('survey_id', $surveys)
								   ->first();
			if(substr_count($qstn, 'Test-1')>0)
				$testOne+=$value->sd->answer;
			else
				$testTwo+=$value->sd->answer;
		}
		return $testOne>0?round((int)$testTwo*100/(int)$testOne, 2):0;
	}
	/**
	* Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
	*/
	public function overallAgreement()
	{
		//	Initialize variables
		$total = 0;
		$invalid = 0;
		$reactiveOne = 0;
		$nonReactiveOne = 0;
		$reactiveTwo = 0;
		$nonReactiveTwo = 0;
		//	Get sdp surveys
		$surveys = $this->surveys->lists('id');
		//	Get questions to be used in the math
		$qstns = array('Test-1 Total Positive', 'Test-1 Total Negative', 'Test-1 Total Invalid', 'Test-2 Total Positive', 'Test-2 Total Negative', 'Test-2 Total Invalid');
		//	Math
		foreach ($qstns as $qstn) {
			$question = Question::idByName($qstn);
			$value = SurveyQuestion::where('question_id', $question)
								   ->whereIn('survey_id', $surveys)
								   ->first();
			if(substr_count($qstn, 'Test-1')>0)
				$total+=$value->sd->answer;
			if(substr_count($qstn, 'Invalid')>0)
				$invalid+=$value->sd->answer;
			if(substr_count($qstn, 'Test-1 Total Positive')>0)
				$reactiveOne+=$value->sd->answer;
			if(substr_count($qstn, 'Test-1 Total Negative')>0)
				$nonReactiveOne+=$value->sd->answer;
			if(substr_count($qstn, 'Test-2 Total Positive')>0)
				$reactiveTwo+=$value->sd->answer;
			if(substr_count($qstn, 'Test-2 Total Negative')>0)
				$nonReactiveTwo+=$value->sd->answer;
		}
		$absReactive = abs($reactiveTwo-$reactiveOne);
		$absNonReactive = abs($nonReactiveTwo-$nonReactiveOne);
		/*if($algorithm == 'Parallel')
			return ($total - $invalid)>0?round((($total - $invalid) - ($absReactive + $absNonReactive)) * 100 / ($total - $invalid), 2):0;
		else*/
			return ($total - $invalid)>0?round(($reactiveTwo+$nonReactiveOne) * 100 / ($total-$invalid), 2):0;
	}
	/**
	* Return Sdp ID given the identifier
	* @param $name the identifier of the sdp
	*/
	public static function idById($id=NULL)
	{
		if($id!=NULL){
			try 
			{
				$sdp = Sdp::where('identifier', $id)->orderBy('name', 'asc')->firstOrFail();
				return $sdp->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The sdp with identifier ` $id ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
}
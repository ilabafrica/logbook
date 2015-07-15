<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sdp extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sdps';
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
	public function positivePercent($name, $surveys){
		$positive = SurveyData::select('answer')
							  ->where('question_id', Question::idByName($name.' Total Positive'))
							  ->whereIn('survey_id', $surveys)
							  ->sum('answer');
		$negative = SurveyData::select('answer')
							  ->where('question_id', Question::idByName($name.' Total Negative'))
							  ->whereIn('survey_id', $surveys)
							  ->sum('answer');
		$indeterminate = SurveyData::select('answer')
							  ->where('question_id', Question::idByName($name.' Total Invalid'))
							  ->whereIn('survey_id', $surveys)
							  ->sum('answer');							  							 
		return round($positive*100/($positive+$negative+$indeterminate), 2);
	}
}
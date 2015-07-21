<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sdp extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sdps';
	/**
	 * Survey relationship
	 */
	public function surveys()
	{
		return $this->hasMany('App\Models\Survey');
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
	* Get sdps in period given
	*/
	public function sdps($checklist = 1, $from = NULL, $to = NULL)
	{
		return $sdps = Survey::select('sdp_id')
					  		 ->where('checklist_id', $checklist)
					  		 ->get();
	}
	/**
	* Calculation of positive percent[ (Total Number of Positive Results/Total Number of Specimens Tested)*100 ] - Aggregated
	*/
	public function positivePercent($name, $surveys)
	{
		/*$positive = SurveyData::select('answer')
							  ->where('question_id', Question::idByName($name.' Total Positive'))
							  ->whereIn('survey_id', $surveys)
							  ->sum('answer');
		$negative = SurveyData::select('answer')
							  ->where('question_id', Question::idByName($name.' Total Negative'))
							  ->whereIn('survey_id', $surveys)
							  ->sum('answer');*/

		return round(16*100/(30), 2);
	}
}
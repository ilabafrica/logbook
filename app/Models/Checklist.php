<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Checklist extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'checklists';
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
	 * Surveys relationship
	 */
	public function surveys()
	{
		return $this->hasMany('App\Models\Survey');
	}
	/**
	 * Sections relationship
	 */
	public function sections()
	{
		return $this->hasMany('App\Models\Section');
	}	
	/**
	* Get sdps in period given
	*/
	public function sdps($from = NULL, $to = NULL)
	{
		return $sdps = Survey::select('sdp_id')
					  		 ->where('checklist_id', $this->id)
					  		 ->get();
	}
	/**
	* Return Checklist ID given the name
	* @param $name the name of the user
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$checklist = Checklist::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $checklist->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The checklist ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	 * Function to calculate level
	 */
	public function level()
	{
		//	Initialize variables
		$total = 0.0;
		$qstns = array();
		$questions = array();
		foreach ($this->surveys as $survey) 
		{
			foreach ($survey->sqs as $sq) 
			{
				$qstns = $sq->lists('question_id');
			}
			$questions = array_merge($questions, $qstns);
		}
		$questions = array_unique($questions);
		foreach ($this->sections as $section) 
		{
			if($section->isScorable())
			{
				foreach ($section->questions as $question) 
				{
					if(in_array($question->id, $questions))
					{
						$sqs = SurveyQuestion::where('question_id', $question->id)->get();
						foreach ($sqs as $sq) 
						{
							if($sq->ss)
								$total+=$sq->ss->score;
						}
					}
				}
			}
		}
		return $total;
	}
	/**
	 * Count unique officers who participated in survey
	 */
	public function officers()
	{
		return $this->surveys->groupBy('qa_officer')->count();
	}
}

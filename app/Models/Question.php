<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lang;


class Question extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'questions';
	//	Constants for type of field
	const CHOICE = 0;
	const DATE = 1;
	const FIELD = 2;
	const TEXTAREA = 3;

	//	Constants for whether field is required
	const REQUIRED = 1;
	//	Constants for whether field is to include tabular display
	const ONESTAR = 1;
	
	/**
	 * responses relationship
	 */
	public function responses()
	{
	  return $this->belongsToMany('App\Models\Response', 'question_responses', 'question_id', 'response_id');
	}
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
	}
}

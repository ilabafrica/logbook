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
	 * Audit field relationship
	 */
	public function children()
	{
		return $this->belongsToMany('App\Models\Question', 'question_parent_child', 'parent_id', 'question_id');
	}
	/**
	 * answers relationship
	 */
	public function answers()
	{
	  return $this->belongsToMany('App\Models\Answer', 'question_answers', 'question_id', 'answer_id');
	}
	/**
	 * answers relationship
	 */
	public function notes()
	{
	  return $this->belongsToMany('App\Models\Note', 'question_notes', 'question_id', 'note_id');
	}
	//	Set parent for audit field if selected
	public function setParent($field){

		$fieldAdded = array();
		$questionId = 0;	

		if(is_array($field)){
			foreach ($field as $key => $value) {
				$fieldAdded[] = array(
					'question_id' => (int)$this->id,
					'parent_id' => (int)$value,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
					);
				$questionId = (int)$this->id;
			}

		}
		// Delete existing parent-child mappings
		DB::table('question_parent_child')->where('question_id', '=', $questionId)->delete();

		// Add the new mapping
		DB::table('question_parent_child')->insert($fieldAdded);
	}
	//	Set parent for audit field if selected
	public function setAnswers($field){

		$fieldAdded = array();
		$questionId = 0;	

		if(is_array($field)){
			foreach ($field as $key => $value) {
				$fieldAdded[] = array(
					'question_id' => (int)$this->id,
					'answer_id' => (int)$value,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
					);
				$questionId = (int)$this->id;
			}

		}
		// Delete existing parent-child mappings
		DB::table('question_answers')->where('question_id', '=', $questionId)->delete();

		// Add the new mapping
		DB::table('question_answers')->insert($fieldAdded);
	}
	//	Set parent for audit field if selected
	public function setNotes($field){

		$fieldAdded = array();
		$questionId = 0;	

		if(is_array($field)){
			foreach ($field as $key => $value) {
				$fieldAdded[] = array(
					'question_id' => (int)$this->id,
					'note_id' => (int)$value,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
					);
				$questionId = (int)$this->id;
			}

		}
		// Delete existing question-note mappings
		DB::table('question_notes')->where('question_id', '=', $questionId)->delete();

		// Add the new mapping
		DB::table('question_notes')->insert($fieldAdded);
	}
	/**
	* Relationship with review questions answers
	*/
	public function qa($review)
	{
		$row = DB::table('review_question_answers')->where('review_id', $review)->where('question_id', $this->id)->lists('answer');
		if(count($row)>0)
			return $row;
	}
	/**
	* Audit notes
	*/
	public function note($review){
		return DB::table('review_notes')->where('review_id', $review)->where('question_id', $this->id)->first();
	}

	/**
	* Audited scores
	*/
	public function points($review)
	{
		return DB::table('review_question_scores')->where('review_id', $review)->where('question_id', $this->id)->first();
	}
	/**
	* Decode audited scores
	*/
	public function decode($review)
	{
		$this->points($review)?$score = $this->points($review)->audited_score:$score="";
		if($score == $this->score)
			return Lang::choice('messages.yes', 2);
		else if($score == 0)
			return Lang::choice('messages.no', 2);
		else if($score == 1)
			return Lang::choice('messages.partial', 2);
		else
			return "";

	}
	/**
	* Comparison to check whether complete/incomplete
	*/
	public function complete($review)
	{
		$ids = Question::find($this->id)->children->lists('id');
		$row = DB::table('review_question_answers')->where('review_id', $review)->whereIn('question_id', $ids)->lists('id');
		if(count($row)>0)
			return $row;
	}
}

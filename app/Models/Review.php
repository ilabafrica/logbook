<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lang;

class Review extends Model {

	protected $table = 'reviews';
	/**
	* Official SLMTA?
	*/
	const OFFICIAL = 1;

	/**
	* Completion Status
	*/
	const INCOMPLETE = 0;
	const COMPLETE = 1;
	const FINALIZED = 2;
	const REJECTED = 3;
	/**
	* Stars
	*/
	const NOTAUDITED = 0;
	const ZEROSTARS = 1;
	const ONESTAR = 2;
	const TWOSTARS = 3;
	const THREESTARS = 4;
	const FOURSTARS = 5;
	const FIVESTARS = 6;

	/**
	* Relationship with laboratory
	*/
	public function lab()
	{
		return $this->belongsTo('App\Models\Lab');
	}
	/**
	* Relationship with user
	*/
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
	/**
	* Relationship with audit Type
	*/
	public function auditType()
	{
		return $this->belongsTo('App\Models\AuditType');
	}
	/**
	* Relationship with assessment Type
	*/
	public function assessment($id)
	{
		return Assessment::find($id);
	}
	/**
	 * Auditors relationship
	 */
	public function assessors()
	{
	 	return $this->belongsToMany('App\Models\User', 'review_assessors', 'review_id', 'assessor_id');
	}
	//	Set auditors for the review
	public function setAssessors($field){

		$fieldAdded = array();
		$reviewId = 0;	

		if(is_array($field)){
			foreach ($field as $key => $value) {
				$fieldAdded[] = array(
					'review_id' => (int)$this->id,
					'assessor_id' => (int)$value,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
					);
				$reviewId = (int)$this->id;
			}

		}
		// Delete existing parent-child mappings
		DB::table('review_assessors')->where('review_id', '=', $reviewId)->delete();

		// Add the new mapping
		DB::table('review_assessors')->insert($fieldAdded);
	}
	/**
	* SLMTA Information
	*/
	public function slmta()
	{
		return DB::table('review_slmta_info')->where('review_id', $this->id)->first();
	}
	/**
	* Stars - Not Audited, 0-5
	*/
	public function stars($id)
	{
		if($id == Review::NOTAUDITED)
			return Lang::choice('messages.not-audited', 1);
		else if($id == Review::ZEROSTARS)
			return Lang::choice('messages.zero-stars', 1);
		else if($id == Review::ONESTAR)
			return Lang::choice('messages.one-star', 1);
		else if($id == Review::TWOSTARS)
			return Lang::choice('messages.two-stars', 1);
		else if($id == Review::THREESTARS)
			return Lang::choice('messages.three-stars', 1);
		else if($id == Review::FOURSTARS)
			return Lang::choice('messages.four-stars', 1);
		else if($id == Review::FIVESTARS)
			return Lang::choice('messages.five-stars', 1);
	}
	/**
	* Laboratory Information
	*/
	public function laboratory()
	{
		return DB::table('review_lab_profiles')->where('review_id', $this->id)->first();
	}
	/**
	* Adequate
	*/
	public function adequate($id)
	{
		if($id == Answer::INSUFFICIENT)
			return Lang::choice('messages.insufficient-data', 1);
		else if($id == Answer::YES)
			return Lang::choice('messages.yes', 1);
		else if($id == Answer::NO)
			return Lang::choice('messages.no', 1);
	}
	/**
	* Action plan 
	*/
	public function plans()
	{
		return DB::table('review_action_plans')->where('review_id', $this->id)->get();
	}
	/**
	* Non-compliancies 
	*/
	public function noncompliance()
	{
		return DB::table('review_notes')->where('review_id', $this->id)->where('non_compliance', Answer::NONCOMPLIANT)->orderBy('question_id')->get();
	}
}
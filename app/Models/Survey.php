<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'survey';
	/**
	 * Checklist relationship
	 */
	public function checklist()
	{
		return $this->belongsTo('App\Models\Checklist');
	}
	/**
	 * Users relationship
	 */
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
	/**
	 * Facility relationship
	 */
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility');
	}
	/**
	 * SurveyData relationship
	 */
	public function data()
	{
		return $this->hasMany('App\Models\SurveyData');
	}
	/**
	 * Count number of questionnaires given qa officer filled
	 */
	public static function questionnaires($officer)
	{
		return count(Survey::where('qa_officer', $officer));
	}
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends Model{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'survey_questions';
    /**
     * Survey relationship
     */
    public function survey()
    {
       return $this->belongsTo('App\Models\Survey');
    }
	/**
	 * Questions relationship
	 */
	public function question()
	{
	   return $this->belongsTo('App\Models\Question');
	}
    /**
     * survey-data relationship
     */
    public function sd()
    {
       return $this->hasOne('App\Models\SurveyData');
    }
    /**
     * Survey-scores relationship
     */
    public function ss()
    {
       return $this->hasOne('App\Models\SurveyScore');
    }
}
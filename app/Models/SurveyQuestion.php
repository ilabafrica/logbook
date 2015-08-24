<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class SurveyQuestion extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'survey_questions';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'survey_id',
        'question_id',
    ];

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
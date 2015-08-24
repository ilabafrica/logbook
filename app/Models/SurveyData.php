<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class SurveyData extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'survey_data';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'survey_question_id',
        'answer',
    ];

	/**
     * SurveyQuestion relationship
     */
    public function sq()
    {
       return $this->belongsTo('App\Models\SurveyQuestion');
    }
}
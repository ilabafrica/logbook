<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class HtcSurveyPageData extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_survey_page_data';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'htc_survey_page_question_id',
        'answer',
    ];
	/**
     * Htc-Survey-Page-Question relationship
     */
    public function htc_survey_page_question()
    {
       return $this->belongsTo('App\Models\HtcSurveyPageQuestion');
    }
}
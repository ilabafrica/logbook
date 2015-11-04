<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class HtcSurveyPageQuestion extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_survey_page_questions';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'htc_survey_page_id',
        'question_id',
          ];
	/**
     * Htc-Survey-Page relationship
     */
    public function hsp()
    {
       return $this->belongsTo('App\Models\HtcSurveyPage');
    }
    /**
     * Question relationship
     */
    public function question()
    {
       return $this->belongsTo('App\Models\Question');
    }
    /**
     * page-data relationship
     */
    public function data()
    {
        return $this->hasOne('App\Models\HtcSurveyPageData');
    }
    /**
     * page relationship
     */
    public function htc_survey_page()
    {
        return $this->belongsTo('App\Models\HtcSurveyPage');
    }
}
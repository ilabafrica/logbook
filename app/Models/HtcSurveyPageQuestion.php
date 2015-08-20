<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HtcSurveyPageQuestion extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_survey_page_questions';
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
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HtcSurveyPageData extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_survey_page_data';
	/**
     * Htc-Survey-Page-Question relationship
     */
    public function question()
    {
       return $this->belongsTo('App\Models\HtcSurveyPageQuestion');
    }
}
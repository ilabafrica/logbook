<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HtcSurveyPage extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_survey_pages';
	/**
     * Survey-Sdp relationship
     */
    public function sdp()
    {
       return $this->belongsTo('App\Models\SurveySdp');
    }
    /**
     * page-questions relationship
     */
    public function questions()
    {
        return $this->hasMany('App\Models\HtcSurveyPageQuestion');
    }
}
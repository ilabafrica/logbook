<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveySdp extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'survey_sdps';
	/**
     * Survey relationship
     */
    public function survey()
    {
       return $this->belongsTo('App\Models\Survey');
    }
    /**
     * Sdp relationship
     */
    public function sdp()
    {
       return $this->belongsTo('App\Models\Sdp');
    }
    /**
     * pages relationship
     */
    public function pages()
    {
        return $this->hasMany('App\Models\HtcSurveyPage');
    }
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class SurveySdp extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'survey_sdps';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'survey_id',
        'sdp_id',
        'comment',
    ];
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
    /**
     * survey-question relationship
     */
    public function sqs()
    {
        return $this->hasMany('App\Models\SurveyQuestion');
    }
}
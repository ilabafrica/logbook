<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class HtcSurveyPage extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_survey_pages';
    use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'survey_sdp_id',
        'page',
         ];
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
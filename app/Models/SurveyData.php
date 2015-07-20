<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyData extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'survey_data';
	/**
     * SurveyQuestion relationship
     */
    public function sq()
    {
       return $this->belongsTo('App\Models\SurveyQuestion');
    }
}
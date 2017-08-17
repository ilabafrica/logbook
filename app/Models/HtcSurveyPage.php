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
     * Survey relationship
     */
    public function survey()
    {
       return $this->belongsTo('App\Models\Survey');
    }
    /**
     * page-questions relationship
     */
    public function questions()
    {
        return $this->hasMany('App\Models\HtcSurveyPageQuestion');
    }
    /**
     * Function to get total tests
     */
    public function totalTests()
    {
        $reactive = Question::where('identifier', 'testreactive')->first();
        $nonreactive = Question::where('identifier', 'nonreactive')->first();
        return $this->questions()->where('question_id', $reactive->id)->first()->data->answer+$this->questions()->where('question_id', $nonreactive->id)->first()->data->answer;
    }
     /**
     * Function to get percent positive
     */
    public function posPercent()
    {
        $total = $this->totalTests();
        $reactive1 = $reactive = Question::where('identifier', 'testreactive')->first();
        $reactive2 = $reactive = Question::where('identifier', 'testreactive1')->first();
        $reactive = $this->questions()->where('question_id', $reactive1->id)->first()->data->answer+$this->questions()->where('question_id', $reactive2->id)->first()->data->answer;
        return round($reactive*100/$total, 2);
    }
    /**
     * Function to get positive agreement
     */
    public function posAgreement()
    {
        $reactive1 = $reactive = Question::where('identifier', 'testreactive')->first();
        $reactive2 = $reactive = Question::where('identifier', 'testreactive1')->first();
        return $this->questions()->where('question_id', $reactive1->id)->first()->data->answer>0?round($this->questions()->where('question_id', $reactive2->id)->first()->data->answer*100/$this->questions()->where('question_id', $reactive1->id)->first()->data->answer, 2):0.00;
    }
    /**
     * Function to get overall agreement
     */
    public function overAgreement()
    {
        $reactive1 = $reactive = Question::where('identifier', 'testreactive')->first();
        $reactive2 = $reactive = Question::where('identifier', 'testreactive1')->first();
        $nonreactive1 = $reactive = Question::where('identifier', 'nonreactive')->first();
        $nonreactive2 = $reactive = Question::where('identifier', 'nonreactive1')->first();
        $invalid1 = $reactive = Question::where('identifier', 'totalinvalid')->first();
        $invalid2 = $reactive = Question::where('identifier', 'totalinvalid1')->first();

        $total = $this->totalTests();
        $invalid = $this->questions()->where('question_id', $invalid1->id)->first()->data->answer+$this->questions()->where('question_id', $invalid2->id)->first()->data->answer;
        $reactive_1 = $this->questions()->where('question_id', $reactive1->id)->first()->data->answer;
        $reactive_2 = $this->questions()->where('question_id', $reactive2->id)->first()->data->answer;
        $nonreactive_1 = $this->questions()->where('question_id', $nonreactive1->id)->first()->data->answer;
        $nonreactive_2 = $this->questions()->where('question_id', $nonreactive2->id)->first()->data->answer;

        $absReactive = abs($reactive_2-$reactive_1);
        $absNonReactive = abs($nonreactive_2-$nonreactive_1);

        return ($total - $invalid)>0?round(($reactive_2+$nonreactive_1) * 100 / ($total-$invalid), 2):0;
    }
    /**
     * htc-survey-page-question relationship given the question id
     */
    public function sq($id)
    {
        return $this->questions->where('question_id', $id)->first();
    }
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Survey extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'surveys';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'qa_officer',
        'facility_id',
        'longitude',
        'latitude',
        'checklist_id',
        'comment',
        'user_id',
    ];
	/**
	 * Checklist relationship
	 */
	public function checklist()
	{
		return $this->belongsTo('App\Models\Checklist');
	}
	/**
	 * Users relationship
	 */
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
	/**
	 * Facility relationship
	 */
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility');
	}
	/**
	 * Sdp relationship
	 */
	public function sdp()
	{
		return $this->belongsTo('App\Models\Sdp');
	}
	/**
	 * SurveyQuestions relationship
	 */
	public function questions()
	{
		return $this->hasMany('App\Models\SurveyQuestion');
	}
	/**
	 * Count number of questionnaires given qa officer filled
	 */
	public static function questionnaires($officer)
	{
		return count(Survey::where('qa_officer', $officer));
	}
	/**
	* Calculation of positive percent[ (Total Number of Positive Results/Total Number of Specimens Tested)*100 ]
	*/
	public function positivePercent(){
		return round($this->positive*100/($this->positive+$this->negative+$this->indeterminate), 2);
	}
	/**
	* Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
	*/
	public function overallAgreement(){
		$total = $this->positive+$this->negative+$this->indeterminate;
		$invalid = $this->htcData->where('test_kit_no', Htc::TESTKIT1)->first()->invalid + $this->htcData->where('test_kit_no', Htc::TESTKIT2)->first()->invalid;
		$absReactive = abs($this->htcData->where('test_kit_no', Htc::TESTKIT2)->first()->reactive - $this->htcData->where('test_kit_no', Htc::TESTKIT1)->first()->reactive);
		$absNonReactive = abs($this->htcData->where('test_kit_no', Htc::TESTKIT2)->first()->non_reactive - $this->htcData->where('test_kit_no', Htc::TESTKIT1)->first()->non_reactive);
		return round((($total - $invalid) - ($absReactive + $absNonReactive)) * 100 / ($total - $invalid), 2);
	}
	/**
	 * survey-me-info relationship
	 */
	public function me()
	{
		return $this->hasOne('App\Models\MeInfo');
	}
	/**
	 * survey-spirt-info relationship
	 */
	public function spirt()
	{
		return $this->hasMany('App\Models\SpirtInfo');
	}

	/**
	 * survey-question relationship
	 */
	public function sqs()
	{
		return $this->hasMany('App\Models\SurveyQuestion');
	}
	/**
	 * SurveySdps relationship
	 */
	public function sdps()
	{
		return $this->hasMany('App\Models\SurveySdp');
	}
}
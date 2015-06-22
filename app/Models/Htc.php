<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Htc extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc';
	//	Constants for test 1, 2 and 3
	const TESTKIT1 = 1;
	const TESTKIT2 = 2;
	const TESTKIT3 = 3;
	/**
	* Relationship with htc Data
	*/
	public function htcData(){
		return $this->HasMany('App\Models\HtcData');
	}
	/**
	* Relationship with site
	*/
	public function site(){
		return $this->belongsTo('App\Models\Site');
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
	* Calculation of positive agreement[ (Total Reactive Results from Test 2/Total Reactive Results from Test 1)*100 ]
	*/
	public function positiveAgreement(){
		return round($this->htcData->where('test_kit_no', Htc::TESTKIT2)->first()->reactive*100/$this->htcData->where('test_kit_no', Htc::TESTKIT1)->first()->reactive, 2);
	}
}
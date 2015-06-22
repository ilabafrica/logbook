<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HtcData extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_test_kits';
	/**
	* Relationship with Htc
	*/
	public function htc(){
		return $this->belongsTo('App\Models\Htc');
	}
	/**
	* Get tests 1, 2 and 3
	*/
	public function testKit($test_kit_no){
		return $this->where('test_kit_no', $test_kit_no)->first();
	}
}

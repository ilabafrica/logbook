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
	/* HtcData relationship	*/
	public function htcData(){
		return $this->HasMany('App\Models\HtcData');
	}
}
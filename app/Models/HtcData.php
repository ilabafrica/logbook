<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HtcData extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_test_kits';
	/* Htc relationship	*/
	public function htc(){
		return $this->belongsTo('App\Models\Htc');
	}
}

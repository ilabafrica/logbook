<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class HtcData extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'htc_test_kits';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'description',
    ];

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

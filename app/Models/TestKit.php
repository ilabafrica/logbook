<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestKit extends Model {
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'kits';

	/**
	* approval status
	*/
	
	const NOTAPPROVED = 0;
	const APPROVED = 1;
	const PENDING = 2;
	const NOTKNOWN = 3;
	
	/**
	* INCOUNTRY APROVAL
	*/
	
	const YES = 1;
	const NO = 0;
	const NA = 2;
	
	/**
	* Relationship with agency
	*/
	public function agency()
	{

		return $this->belongsTo('App\Models\Agency', 'approval_agency_id');

	}
}

<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestKit extends Model {
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'test_kits';

	/**
	* approval status
	*/
	
	const NOTAPPROVED = 0;
	const APPROVED = 1;
	const PENDING = 2;
	const NOTKNOWN = 3;
	

	/**
	* APPROVAL AGENCY
	*/
	const NA = 1;
	const USAID = 2;
	const WHOANDNATIONAL = 3;
	const OTHER = 4;


	/**
	* INCOUNTRY APROVAL
	*/
	
	const YES = 1;
	const NO = 0;
	const NA = 2;
	
	
}
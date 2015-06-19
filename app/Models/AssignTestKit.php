<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignTestKit extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'assign_testkits';

	/**
		* Stock Availability
		*/
		const STOCKNOTAVAILABLE = 0;
		const STOCKAVAILABLE = 1;
		
	/**
	* Relationship with sites
	*/
	public function site()
	{
		return $this->belongsTo('App\Models\Site');
	}
	/**
	* Relationship with sites
	*/
	public function testkit()
	{

		return $this->belongsTo('App\Models\TestKit', 'kit_name_id');
	}
}
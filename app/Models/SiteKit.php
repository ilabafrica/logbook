<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteKit extends Model {
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'site_test_kits';
 	/* Stock availability */
 	const AVAILABLE = 1;
 	const NOTAVAILABLE = 2;
	/**
	* Relationship with kits
	*/
	public function kit()
	{

		return $this->belongsTo('App\Models\TestKit', 'kit_id');

	}
	/**
	* Relationship with sites
	*/
	public function site()
	{

		return $this->belongsTo('App\Models\Site', 'site_id');

	}
}
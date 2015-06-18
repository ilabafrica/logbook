<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Serial extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'serials';



/**
	* Relationship with testkits
	*/
	public function site()
	{
	 return $this->hasMany('App\Models\Site', 'test_site_id');
	}


}


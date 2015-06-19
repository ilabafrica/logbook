<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCounty extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sub_counties';
	/**
	* Relationship with county
	*/
	public function county()
	{

		return $this->belongsTo('App\Models\County');

	}
}

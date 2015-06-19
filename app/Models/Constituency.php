<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\County;

class Constituency extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'constituencies';
   

	/**
	* Relationship with county
	*/
	public function county()
	{
		return $this->belongsTo('App\Models\County');
	}
}

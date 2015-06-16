<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Town extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'towns';


/**
* Relationship with constituency
*/
public function constituency()
{
 return $this->belongsTo('App\Models\Constituency');
}
}

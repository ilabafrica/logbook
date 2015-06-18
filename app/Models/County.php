<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class County extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'counties';


/**
* Relationship with constituencies
*/
public function constituencies()
{

 return $this->hasMany('constituencies');

}
}

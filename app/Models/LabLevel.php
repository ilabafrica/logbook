<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabLevel extends Model {
	 use SoftDeletes;
     protected $dates = ['deleted_at'];

	protected $table = 'lab_levels';

}

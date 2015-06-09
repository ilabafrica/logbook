<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabAffiliation extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'lab_affiliations';

}

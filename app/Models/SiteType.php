<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteType extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'site_types';

}

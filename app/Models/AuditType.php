<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditType extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'audit_types';

	/**
	 * Audit section relationship
	 */
	public function sections()
	{
	  return $this->hasMany('App\Models\Section');
	}
	/**
	 * Reviews relationship
	 */
	public function reviews()
	{
	  return $this->hasMany('App\Models\Review');
	}
}

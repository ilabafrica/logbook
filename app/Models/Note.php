<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model {

	protected $table = 'notes';
	/**
	* Relationship with auditType
	*/
	public function auditType()
	{
	 return $this->belongsTo('App\Models\AuditType');
	}

}

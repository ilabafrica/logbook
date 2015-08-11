<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditType extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'audit_types';
	/**
	* Return audit_type_id given the name
	* @param $name the name of the audit type
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$audit_type = AuditType::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $audit_type->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The audit type ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}	
}
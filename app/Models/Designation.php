<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Designation extends Model implements Revisionable {
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'designations';
 	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];
 	/**
	* Return designation id given the name
	* @param $name the name of the designation
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$designation = Designation::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $designation->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The designation ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
}
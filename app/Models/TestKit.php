<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class TestKit extends Model implements Revisionable {
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'hiv_test_kits';
 	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];
 	/**
	* Return kit id given the name
	* @param $name the name of the kit
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$kit = TestKit::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $kit->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The kit ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
}
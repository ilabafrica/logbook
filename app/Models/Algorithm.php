<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Algorithm extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'algorithms';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];
	/**
	* Return algorithm_id given the name
	* @param $name the name of the algorithm
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$algorithm = Algorithm::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $algorithm->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The algorithm ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
}
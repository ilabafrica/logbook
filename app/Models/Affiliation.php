<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Affiliation extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'affiliations';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];
	/**
	* Return affiliation_id given the name
	* @param $name the name of the affiliation
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$affiliation = Affiliation::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $affiliation->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The affiliation ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Tier extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tiers';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
    ];
    /**
    * Relationship with sdps
    */
    public function sdp()
    {
        return $this->belongsTo('App\Models\Sdp');
    }
    /**
    * Return tier ID given the name
    * @param $name the name of the tier
    */
    public static function idByName($name=NULL)
    {
        if($name!=NULL){
            try 
            {
                $tier = Tier::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
                return $tier->id;
            } catch (ModelNotFoundException $e) 
            {
                Log::error("The tier ` $name ` does not exist:  ". $e->getMessage());
                //TODO: send email?
                return null;
            }
        }
        else{
            return null;
        }
    }
}
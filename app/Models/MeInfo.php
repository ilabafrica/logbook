<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class MeInfo extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'survey_me_info';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'survey_id',
        'audit_type_id',
        'algorithm_id',
        'screening',
        'confirmatory',
        'tie_breaker',
    ];
    /**
	* Relationship with survey
	*/
	
    public function survey()
	{
		return $this->belongsTo('App\Models\Survey');
	}
}
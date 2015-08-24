<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class SiteKit extends Model implements Revisionable{
	use SoftDeletes;
 	protected $dates = ['deleted_at'];
 	protected $table = 'site_test_kits';
 	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'site_id',
        'kit_id',
        'lot_no',
        'user_id',
    ];
 	/* Stock availability */
 	const AVAILABLE = 1;
 	const NOTAVAILABLE = 2;
	/**
	* Relationship with kits
	*/
	public function kit()
	{

		return $this->belongsTo('App\Models\TestKit', 'kit_id');

	}
	/**
	* Relationship with sites
	*/
	public function site()
	{

		return $this->belongsTo('App\Models\Site', 'site_id');

	}
}
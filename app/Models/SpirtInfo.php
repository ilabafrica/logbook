<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpirtInfo extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'survey_spirt_info';
	/**
	* Relationship with survey
	*/
	public function survey()
	{
		return $this->belongsTo('App\Models\Survey');
	}
}
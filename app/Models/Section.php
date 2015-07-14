<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sections';

	/**
	 * Questions relationship
	 */
	public function questions()
	{
		return $this->hasMany('App\Models\Question');
	}
	/**
	 * Checklist relationship
	 */
	public function checklist()
	{
		return $this->belongsTo('App\Models\Checklist');
	}
}
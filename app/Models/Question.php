<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lang;


class Question extends Model {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'questions';
	//	Constants for type of field
	const CHOICE = 0;
	const DATE = 1;
	const FIELD = 2;
	const TEXTAREA = 3;

	//	Constants for whether field is required
	const REQUIRED = 1;
	//	Constants for whether field is to include tabular display
	const ONESTAR = 1;
	
	/**
	 * responses relationship
	 */
	public function responses()
	{
	  return $this->belongsToMany('App\Models\Response', 'question_responses', 'question_id', 'response_id');
	}
	
}

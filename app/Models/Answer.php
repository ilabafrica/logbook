<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;

class Answer extends Model {

	protected $table = 'responses';
	/**
	* Responses for questions
	*/
	const NO = 0;
	const YES = 1;	
	const PARTIAL = 0.5;
	/**
	* Return respective score for a response
	*/
	public function point()
	{
		return 0;
	}
}

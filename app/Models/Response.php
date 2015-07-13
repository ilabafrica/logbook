<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;

class Response extends Model {

	protected $table = 'answers';
	/**
	* Responses for questions
	*/
	const NO = 0;
	const YES = 1;	
	const PARTIAL = 0.5;	
}

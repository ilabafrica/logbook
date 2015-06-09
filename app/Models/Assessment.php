<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model {

	protected $table = 'assessments';
	/**
	* Return answer constant for the given choice
	* @param $name the choice
	*/
	/**
	* Return Assessment type id given the name
	* @param $name the name of the assessment type
	*/
	public static function idByName($name)
	{
		try 
		{
			$assessment = Assessment::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
			return $assessment->id;
		}
		catch (ModelNotFoundException $e) 
		{
			Log::error("The Assessment type ` $name ` does not exist:  ". $e->getMessage());
			//TODO: send email?
			return null;
		}
	}
}
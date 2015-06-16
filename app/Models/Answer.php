<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;

class Answer extends Model {

	protected $table = 'answers';
	/**
	* Answers for questions
	*/
	const INSUFFICIENT = 0;
	const YES = 1;
	const NO = 2;
	/**
	* Notes - Compliant(Comment) vs Non/compliant
	*/
	const NONCOMPLIANT = 1;
	/**
	* Return answer constant for the given choice
	* @param $name the choice
	*/
	public static function adequate($answer=NULL)
	{
		if($answer == Lang::choice('messages.yes', 1))
			return Answer::YES;
		else if($answer == Lang::choice('messages.no', 1))
			return Answer::NO;
		else if($answer == Lang::choice('messages.insufficient-data', 1))
			return Answer::INSUFFICIENT;
		else if($answer == Lang::choice('messages.non-compliant', 1))
			return Answer::NONCOMPLIANT;
		else
			return NULL;
	}
	/**
	* Return Answer ID given the name
	* @param $name the name of the user
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$answer = Answer::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $answer->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The Answer ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
}

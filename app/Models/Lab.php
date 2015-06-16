<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lab extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'labs';


	/**
	* Relationship with county
	*/
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility');
	}

	/**
	* Relationship with labLevel
	*/
	public function labLevel()
	{
		return $this->belongsTo('App\Models\LabLevel');
	}

	/**
	* Relationship with labAffiliation
	*/
	public function labAffiliation()
	{
		return $this->belongsTo('App\Models\LabAffiliation');
	}

	/**
	* Relationship with labType
	*/
	public function labType()
	{
		return $this->belongsTo('App\Models\LabType');
	}
	/**
	* Return Lab ID given the name
	* @param $name the name of the laboratory
	*/
	public static function labIdName($name)
	{
		try 
		{
			$lab = Facility::where('name', $name)->orderBy('name', 'asc')->firstOrFail()->lab->first();
			return $lab->id;
		} catch (ModelNotFoundException $e) 
		{
			Log::error("The Laboratory ` $name ` does not exist:  ". $e->getMessage());
			//TODO: send email?
			return null;
		}
	}
}

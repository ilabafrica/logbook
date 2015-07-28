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
	/**
	 * Check if section has scorable questions
	 */
	public function isScorable()
	{
		$array = array();
		foreach ($this->questions as $question) {
			$answers = array();
			if($question->answers->count()>0)
			{	
				foreach ($question->answers as $response) 
				{
					if($response->score>0)
						array_push($answers, $response->id);
				}
			}
			if(count($answers)>0)
				array_push($array, $question->id);
		}
		if(count($array)>0)
			return true;
		else
			return false;
	}
}
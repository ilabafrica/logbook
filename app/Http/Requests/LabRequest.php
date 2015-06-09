<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\LabLevel;
use App\Models\LabAffiliation;
use App\Models\LabType;
use App\Models\Facility;

class LabRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		
		return [
		'facility'=> 'required',
		'lab_level'=> 'required',
		'lab_affiliation' =>'required',
		'lab_type' =>'required'
		
        ];
	}
	
}

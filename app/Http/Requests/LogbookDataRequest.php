<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\FacilityOwner;
use App\Models\FacilityType;
use App\Models\Facility;

class LogbookDataRequest extends Request {

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
		$id = $this->ingnoreId();
		return [
		
		'test-site'=> 'required',
		
		
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		
	}

}

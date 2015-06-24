<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\County;
use App\Models\SiteType;
use App\Models\Facility;
use App\Models\Site;

class SiteRequest extends Request {

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
		
		'name'=> 'required',
		
		
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		
	}

}

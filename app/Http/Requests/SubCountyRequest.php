<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\SubCounty;

class SubCountyRequest extends Request {

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
            'name'   => 'required|unique:sub_counties,name,'.$id,
            'county_id'   => 'required:sub_counties,county_id,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('subCounty');
		$name = $this->input('name');
		return SubCounty::where(compact('id', 'name'))->exists() ? $id : '';
	}

}

<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Agency;

class AgencyRequest extends Request {

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
            'name'   => 'required|unique:facility_types,name,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('agency');
		$name = $this->input('name');
		return Agency::where(compact('id', 'name'))->exists() ? $id : '';
	}

}

<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Constituency;

class ConstituencyRequest extends Request {

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
            'name'   => 'required|unique:constituencies,name,'.$id,
            'county_id'   => 'required:constituencies,county_id,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('constituency');
		$name = $this->input('name');
		return Constituency::where(compact('id', 'name'))->exists() ? $id : '';
	}

}

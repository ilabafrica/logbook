<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\County;

class CountyRequest extends Request {

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
            'name'   => 'required|unique:counties,name,'.$id,
            'hq'   => 'required|unique:counties,hq,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('county');
		$name = $this->input('name');
		return County::where(compact('id', 'name'))->exists() ? $id : '';
	}

}

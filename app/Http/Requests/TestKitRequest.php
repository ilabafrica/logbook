<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\TestKit;

class TestKitRequest extends Request {

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
            'full_name'   => 'required|unique:test_kits,full_name,'.$id,
            'short_name'   => 'required|unique:test_kits,short_name,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('testKit');
		$full_name = $this->input('full_name');
		$short_name = $this->input('short_name');
		return TestKit::where(compact('id', 'full_name', 'short_name'))->exists() ? $id : '';
	}
}
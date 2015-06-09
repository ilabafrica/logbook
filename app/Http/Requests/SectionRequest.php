<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Section;

class SectionRequest extends Request {

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
            'name'   => 'required|unique:sections,name,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('section');
		$name = $this->input('name');
		return Section::where(compact('id', 'name'))->exists() ? $id : '';
	}

}

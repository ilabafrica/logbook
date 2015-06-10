<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
//use App\Models\County;

class ImportTestKitRequest extends Request {

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
            'data-manager'   => 'required',
            
        ];
	}
	public function ingnoreId(){
		
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	
}

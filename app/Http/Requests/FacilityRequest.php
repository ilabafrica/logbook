<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\FacilityOwner;
use App\Models\FacilityType;
use App\Models\Town;
use App\Models\Facility;

class FacilityRequest extends Request {

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
		'code'=> 'required|unique:facilities,code,'.$id,
		'name'=> 'required',
		'facility_type' =>'required',
		'facility_owner' =>'required',
		'operational_status'=>'required'
		
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('facility');
		$code = $this->input('code');
		return Facility::where(compact('id', 'code'))->exists() ? $id : '';
	}

}

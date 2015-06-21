<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Htc;

class HtcRequest extends Request {

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
            'site'   => 'required:htc,site_id,'.$id,
            'start_date'   => 'required:htc,start_date,'.$id,
            'end_date'   => 'required:htc,end_date,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('htc');
		$site = $this->input('site');
		$start_date = $this->input('start_date');
		$end_date = $this->input('end_date');
		return Htc::where(compact('id', 'site', 'start_date'. 'end_date'))->exists() ? $id : '';
	}

}

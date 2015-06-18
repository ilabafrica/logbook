<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Facility;
use App\Models\Site;
use App\Models\TestKit;
class SummaryParallelRequest extends Request {

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
		
			
		
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		
	}

}

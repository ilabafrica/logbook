<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\SiteKit;

class SiteKitRequest extends Request {

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
            'kit'   => 'required:site_test_kits,kit_id,'.$id,
            'site'   => 'required:site_test_kits,site_id,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('siteKit');
		$kit_id = $this->input('kit');
		$site_id = $this->input('site');
		return SiteKit::where(compact('id', 'kit_id', 'site_id'))->exists() ? $id : '';
	}
}
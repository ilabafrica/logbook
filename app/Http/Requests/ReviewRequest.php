<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Review;

class ReviewRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return false;
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
            'user_id'   => 'required:reviews,user_id,'.$id,
            'lab_id'   => 'required:reviews,lab_id,'.$id,
            'audit_type_id'   => 'required:reviews,audit_type_id,'.$id,
            'status'   => 'required:reviews,status,'.$id,
            'update_user_id'   => 'required:reviews,update_user_id,',
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('review');
		$user_id = $this->input('user_id');
		$lab_id = $this->input('lab_id');
		$audit_type_id = $this->input('audit_type_id');
		$status = $this->input('status');
		$update_user_id = $this->input('update_user_id');
		return Review::where(compact('id', 'user_id', 'lab_id', 'audit_type_id', 'status'))->exists() ? $id : '';
	}
}
<?php 
namespace App\Http\Requests\Auth;
 
use Illuminate\Foundation\Http\FormRequest;
 
class LoginRequest extends FormRequest {
    public function rules() {
        return [
            'username' => 'required' ,
            'password' => 'required' ,
        ];
    }
 
    public function remember() {
        return $this->has( 'remember' );
    }
 
    public function credentials() {
        return $this->only( 'username' , 'password' );
    }
 
    public function authorize() {
        return true;
    }
}
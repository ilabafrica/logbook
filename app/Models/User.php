<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {
	use SoftDeletes;
    protected $dates = ['deleted_at'];

	const MALE = 0;
	const FEMALE = 1;

	//	Default password
	const DEFAULT_PASSWORD = '123456';

	use Authenticatable, CanResetPassword;
	use EntrustUserTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
	/**
	 * Get the admin user currently the first user
	 *
	 * @return User model
	 */
	public static function getAdminUser()
	{
		return User::find(1);
	}
	/**
	 * Relationship with reviews
	 *
	 * @return Review object
	 */
	public function reviews()
	{
		return $this->hasMany('App\Models\Review');
	}
	/**
	* Return User ID given the name
	* @param $name the name of the user
	*/
	public static function userIdName($name)
	{
		try 
		{
			$user = User::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
			return $user->id;
		} catch (ModelNotFoundException $e) 
		{
			Log::error("The User ` $name ` does not exist:  ". $e->getMessage());
			//TODO: send email?
			return null;
		}
	}	
}

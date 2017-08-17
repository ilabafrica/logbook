<?php namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
	/**
    * Function for getting the admin role, currently the first user
    *  
    */
    public static function getAdminRole()
    {
        return Role::find(1);
    }
    /**
	* Return Role ID given the name
	* @param $name the name of the role
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$role = Role::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $role->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The role ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
}
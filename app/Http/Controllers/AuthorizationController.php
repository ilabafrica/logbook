<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\RoleUserTier;
use App\Http\Requests\AuthorizationRequest;

use DB;
use Input;

class AuthorizationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all users
		$users = User::all();
		//	Get all roles
		$roles = Role::all();
		//	Get all counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = SubCounty::lists('name', 'id');
		
		return view('authorization.index', compact('users', 'roles','counties', 'subCounties'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all users
		$users = User::all();
		//	Get all roles
		$roles = Role::all();
		//	Get all counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = SubCounty::lists('name', 'id');
		
		return view('authorization.index', compact('users', 'roles', 'counties', 'subCounties'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(AuthorizationRequest $request)
	{
		$arrayUserRoleMapping = $request->userRoles;
		$users = User::all();
		$roles = Role::all();

		foreach ($users as $userkey => $user) {
			foreach ($roles as $roleKey => $role) {
				
				$county = Input::get('county'.$user->id);
				$sub_county = Input::get('sub_county'.$user->id);
				//If checkbox is clicked attach the role
				if(!empty($arrayUserRoleMapping[$userkey][$roleKey]))
				{
					$user->detachRole($role);
					$user->attachRole($role);
					if(($county || $sub_county) && $role != Role::getAdminRole()){
						$county?$tier_id=$county:$tier_id=$sub_county;
						$tier = RoleUserTier::where('user_id', $user->id)
											->where('role_id', $role->id)
											->first();
						if($tier){
							$userTier = RoleUserTier::find($tier->id);
							$userTier->user_id = $user->id;
							$userTier->role_id = $role->id;
							$userTier->tier = $tier_id;
							$userTier->save();
						}
						else{
							$userTier = new RoleUserTier;
							$userTier->user_id = $user->id;
							$userTier->role_id = $role->id;
							$userTier->tier = $tier_id;
							$userTier->save();
						}
					}
				}
				//If checkbox is NOT clicked detatch the role
				elseif (empty($arrayUserRoleMapping[$userkey][$roleKey])) {
					$tier = RoleUserTier::where('user_id', $user->id)
											->where('role_id', $role->id)
											->first();
					if($tier)
							$tier->delete();
					$user->detachRole($role);
				}
			}
		}

		return redirect('authorization')->with('message', 'Authorization created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}

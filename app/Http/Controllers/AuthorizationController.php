<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\AuthorizationRequest;

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
		
		return view('authorization.index', compact('users', 'roles'));
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
		
		return view('authorization.index', compact('users', 'roles'));
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
				//If checkbox is clicked attach the role
				if(!empty($arrayUserRoleMapping[$userkey][$roleKey]))
				{
					$user->detachRole($role);
					$user->attachRole($role);
				}
				//If checkbox is NOT clicked detatch the role
				elseif (empty($arrayUserRoleMapping[$userkey][$roleKey])) {
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

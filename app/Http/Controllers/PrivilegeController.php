<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\PrivilegeRequest;
use App\Models\Permission;
use App\Models\Role;
use Response;
use Session;

class PrivilegeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all permissions
		$permissions = Permission::all();
		//	Get all roles
		$roles = Role::all();
		return view('privilege.index', compact('permissions', 'roles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all permissions
		$permissions = Permission::all();
		//	Get all roles
		$roles = Role::all();
		return view('privilege.index', compact('permissions', 'roles'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PrivilegeRequest $request)
	{
		$arrayPermissionRoleMapping = $request->permissionRoles;
		$permissions = Permission::all();
		$roles = Role::all();

		foreach ($permissions as $permissionkey => $permission) {
			foreach ($roles as $roleKey => $role) {
				//If checkbox is clicked attach the permission
				if(!empty($arrayPermissionRoleMapping[$permissionkey][$roleKey]))
				{   $role->detachPermission($permission);
					$role->attachPermission($permission);
				}
				//If checkbox is NOT clicked detatch the permission
				elseif (empty($arrayPermissionRoleMapping[$permissionkey][$roleKey])) {
					$role->detachPermission($permission);
				}
			}
		}

        return redirect('privilege')->with('message', 'Privilege created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Permission
		$permission = Permission::find($id);
		//show the view and pass the $permission to it
		return view('permission.show', compact('permission'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$permission = Permission::find($id);

        return view('permission.edit', compact('permission'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(PermissionRequest $request, $id)
	{
		$permission = Permission::findOrFail($id);
		$permission->name = $request->name;
        $permission->description = $request->description;
        $permission->user_id = 1;

        $permission->save();

        return redirect('permission')->with('message', 'Permission successfully updated.');
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
	/**
	 * Check if role is admin
	 *
	 * @return Response
	 */
	public static function checkRole()
	{
		return Role::getAdminRole();
	}
}

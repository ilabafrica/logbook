<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use Response;
use Session;

class PermissionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all permissions
		$permissions = Permission::all();
		return view('permission.index')->with('permissions', $permissions);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('permission.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PermissionRequest $request)
	{
		$permission = new Permission;
        $permission->name = $request->name;
        $permission->display_name = $request->display_name;
        $permission->description = $request->description;
        //$permission->user_id = 1;
        $permission->save();

        return redirect('permission')->with('message', 'Permission created successfully.');
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
        //$permission->user_id = 1;

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

}

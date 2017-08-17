<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\DesignationRequest;
use App\Models\Designation;
use Response;
use Auth;
use Lang;

class DesignationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$designations = Designation::all();
		
		return view('designation.index', compact('designations'));
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{ 
		return view('designation.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(DesignationRequest $request)
	{

		$designation = new Designation;
        $designation->name = $request->name;
        $designation->description = $request->description;
        $designation->user_id = Auth::user()->id;
        $designation->save();

        return redirect('designation')->with('message', Lang::choice('messages.record-successfully-saved', 1));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Designation
		$designation = Designation::find($id);
		//show the view and pass the $town to it
		return view('designation.show', compact('designation'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get Designation
		$designation = Designation::find($id);
		
        return view('designation.edit', compact('designation'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(DesignationRequest $request, $id)
	{
		
		$designation = Designation::findOrFail($id);
        $designation->name = $request->name;
        $designation->description = $request->description;
        $designation->user_id = Auth::user()->id;
        $designation->save();

        return redirect('designation')->with('message', Lang::choice('messages.record-successfully-updated', 1));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		$designation= Designation::find($id);
		$designation->delete();
		return redirect('designation')->with('message', Lang::choice('messages.record-successfully-deleted', 1));
	}
	public function destroy($id)
	{
		//
	}
}

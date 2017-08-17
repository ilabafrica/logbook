<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\FacilityOwnerRequest;
use App\Models\FacilityOwner;
use Response;
use Auth;

class FacilityOwnerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all facility owners
		$facilityOwners = FacilityOwner::all();
		return view('mfl.owner.index', compact('facilityOwners'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('mfl.owner.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(FacilityOwnerRequest $request)
	{
		$facilityOwner = new FacilityOwner;
        $facilityOwner->name = $request->name;
        $facilityOwner->description = $request->description;
        $facilityOwner->user_id = Auth::user()->id;;
        $facilityOwner->save();

        return redirect('facilityOwner')->with('message', 'Facility owner created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Facility owner
		$facilityOwner = FacilityOwner::find($id);
		//show the view and pass the $facilityOwner to it
		return view('mfl.owner.show', compact('facilityOwner'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$facilityOwner = FacilityOwner::find($id);

        return view('mfl.owner.edit', compact('facilityOwner'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(FacilityOwnerRequest $request, $id)
	{
		$facilityOwner = FacilityOwner::findOrFail($id);;
        $facilityOwner->name = $request->name;
        $facilityOwner->description = $request->description;
        $facilityOwner->user_id = Auth::user()->id;;
        $facilityOwner->save();

        return redirect('facilityOwner')->with('message', 'Facility owner updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$facilityOwner= FacilityOwner::find($id);
		$facilityOwner->delete();
		return redirect('facilityOwner')->with('message', 'FacilityOwner deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}

}

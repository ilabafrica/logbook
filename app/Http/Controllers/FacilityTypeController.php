<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\FacilityTypeRequest;
use App\Models\FacilityType;
use Response;
use Auth;

class FacilityTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all facility types
		$facilityTypes = FacilityType::all();
		return view('mfl.type.index', compact('facilityTypes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('mfl.type.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(FacilityTypeRequest $request)
	{
		$facilityType = new FacilityType;
        $facilityType->name = $request->name;
        $facilityType->description = $request->description;
        $facilityType->user_id = Auth::user()->id;;
        $facilityType->save();

        return redirect('facilityType')->with('message', 'Facility type created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Facility type
		$facilityType = FacilityType::find($id);
		//show the view and pass the $facilityType to it
		return view('mfl.type.show', compact('facilityType'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$facilityType = FacilityType::find($id);

        return view('mfl.type.edit', compact('facilityType'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(FacilityTypeRequest $request, $id)
	{
		$facilityType = FacilityType::findOrFail($id);;
        $facilityType->name = $request->name;
        $facilityType->description = $request->description;
        $facilityType->user_id = Auth::user()->id;;
        $facilityType->save();

        return redirect('facilityType')->with('message', 'Facility type updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$facilityType= FacilityType::find($id);
		$facilityType->delete();
		return redirect('facilityType')->with('message', 'FacilityType deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}

}

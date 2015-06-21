<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\FacilityRequest;
use App\Models\Facility;
use App\Models\County;
use App\Models\FacilityType;
use App\Models\FacilityOwner;
use App\Models\Town;
use App\Models\Title;
use Response;
use Auth;

class FacilityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all facilities
		$facilities = Facility::all();
		return view('mfl.facility.index', compact('facilities'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all facility types
		$facilityTypes = FacilityType::lists('name', 'id');
		//	Get all facility owners
		$facilityOwners = FacilityOwner::lists('name', 'id');

		$counties = County::lists('name', 'id');
		return view('mfl.facility.create', compact('facilityTypes', 'facilityOwners', 'counties'));

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(FacilityRequest $request)
	{
		$town = new Facility;
		$town->code = $request->code;
        $town->name = $request->name;
        $town->facility_type_id = $request->facility_type;
        $town->facility_owner_id = $request->facility_owner;
        $town->reporting_site = $request->reporting_site;
        $town->county_id = $request->county;
        $town->nearest_town = $request->nearest_town;
        $town->landline = $request->landline;
        $town->mobile = $request->mobile;
        $town->email = $request->email;
        $town->address = $request->address;
        $town->in_charge = $request->in_charge;
        $town->operational_status = $request->operational_status;
        $town->latitude = $request->latitude;
        $town->longitude = $request->longitude;
        $town->user_id = Auth::user()->id;
        $town->save();

        return redirect('facility')->with('message', 'Facility created successfully.');
        

        
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a facility
		$facility = Facility::find($id);
		//show the view and pass the $town to it
		return view('mfl.facility.show', compact('facility'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get facility
		$facility = Facility::find($id);
		//	Get all facility types
		$facilityTypes = FacilityType::lists('name', 'id');
		//	Get initial facility type
		$facilityType = $facility->facility_type_id;
		//	Get all facility owners
		$facilityOwners = FacilityOwner::lists('name', 'id');
		//	Get initial facility owner
		$facilityOwner = $facility->facility_owner_id;
		
		$status = $facility->operational_status;

        return view('mfl.facility.edit', compact('facility', 'facilityTypes', 'facilityOwners','facilityType', 'facilityOwner', 'town', 'title', 'status'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(FacilityRequest $request, $id)
	{
		$town = Facility::findOrFail($id);
       $town->code = $request->code;
        $town->name = $request->name;
        $town->facility_type_id = $request->facility_type;
        $town->facility_owner_id = $request->facility_owner;
        $town->reporting_site = $request->reporting_site;
        $town->county_id = $request->county;
        $town->nearest_town = $request->nearest_town;
        $town->landline = $request->landline;
        $town->mobile = $request->mobile;
        $town->email = $request->email;
        $town->address = $request->address;
        $town->in_charge = $request->in_charge;
        $town->operational_status = $request->operational_status;
        $town->latitude = $request->latitude;
        $town->longitude = $request->longitude;
        $town->user_id = Auth::user()->id;
        $town->save();

        return redirect('facility')->with('message', 'Facility updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		$facility= Facility::find($id);
		$facility->delete();
		return redirect('facility')->with('message', 'Facility deleted successfully.');
	}

	public function destroy($id)
	{
		//
	}
	public function import()
	{
		//	show the view 
		return view('mfl.facility.import');
	}
}

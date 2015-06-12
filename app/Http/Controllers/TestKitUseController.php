<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TestKitRequest;
use App\Models\Facility;
use Response;
use Auth;

class TestKitUseController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		return view('report.testkituse.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TrendReportRequest $request)
	{
		$town = new Site;
		$town->code = $request->code;
        $town->name = $request->name;
        $town->facility_type_id = $request->facility_type;
        $town->facility_owner_id = $request->facility_owner;
        $town->reporting_to = $request->reporting_to;
        $town->nearest_town = $request->nearest_town;
        $town->landline = $request->landline;
        $town->mobile = $request->mobile;
        $town->email = $request->email;
        $town->address = $request->address;
        $town->in_charge = $request->in_charge;
        $town->operational_status = $request->operational_status;
        $town->latitude = $request->latitude;
        $town->longitude = $request->longitude;
        $town->user_id = Auth::user()->id;;
        $town->save();

        return redirect('site')->with('message', 'Site created successfully.');
        

        
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
		$site = Site::find($id);
		//show the view and pass the $town to it
		return view('management.site.show', compact('site'));
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
		$counties = County::lists('name', 'id');
		//	Get initial facility owner
		$county = $county->county_id;
		
		

        return view('mfl.facility.edit', compact('facility', 'facilityTypes', 'counties','facilityType', 'county'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(FacilityRequest $request, $id)
	{
		$town = Site::findOrFail($id);;
        $town->code = $request->code;
        $town->name = $request->name;
        $town->facility_type_id = $request->facility_type;
        $town->facility_owner_id = $request->facility_owner;
        $town->reporting_to = $request->reporting_to;
        $town->nearest_town = $request->nearest_town;
        $town->landline = $request->landline;
        $town->mobile = $request->mobile;
        $town->email = $request->email;
        $town->address = $request->address;
        $town->in_charge = $request->in_charge;
        $town->operational_status = $request->operational_status;
         $town->latitude = $request->latitude;
        $town->longitude = $request->longitude;
        $town->user_id = Auth::user()->id;;
        $town->save();

        return redirect('site')->with('message', 'Site updated successfully.');
       
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		$site= Site::find($id);
		$site->delete();
		return redirect('site')->with('message', 'Site deleted successfully.');
	}

	public function destroy($id)
	{
		//
	}

}

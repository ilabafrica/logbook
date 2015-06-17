<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SiteRequest;
use App\Models\Facility;
use App\Models\SiteType;
use App\Models\County;
use App\Models\Site;
use Response;
use Auth;

class SiteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all sites
		$sites = Site::all();
		return view('management.site.site.index', compact('sites'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all facilities
		$facilities = Facility::lists('name', 'id');
		//	Get all site types
		$siteTypes = SiteType::lists('name', 'id');
		//	Get all counties
		$counties = County::lists('name', 'id');
		
		return view('management.site.site.create', compact('facilities','siteTypes', 'counties'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SiteRequest $request)
	{
		$town = new Site;
		$town->facility_id = $request->facility;
        $town->site_id = $request->site_id;
        $town->site_name = $request->site_name;
        $town->site_type_id = $request->site_type;
        $town->address = $request->address;
        $town->nearest_town = $request->nearest_town;
        $town->county_id = $request->county;
        $town->department = $request->department;
        $town->landline = $request->landline;
        $town->mobile = $request->mobile;
        $town->email = $request->email;
        $town->in_charge = $request->in_charge;
        $town->latitude = $request->latitude;
        $town->longitude = $request->longitude;
        $town->user_id = Auth::user()->id;
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
		//sites
		$site = Site::find($id);
		//	Get facility
		$facility = Facility::find($id);
		//	Get all site types
		$siteTypes = SiteType::lists('name', 'id');
		//	Get initial facility type
		$siteType = $facility->site_type_id;
		//	Get all facility owners
		$counties = County::lists('name', 'id');
		//	Get initial facility owner
		$county = $county->county_id;
		
		

        return view('management.site.edit', compact('site','facility', 'siteTypes', 'counties','siteType', 'county'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(FacilityRequest $request, $id)
	{
		$town = Site::findOrFail($id);
        $town->facility_id = $request->facility_id;
        $town->site_id = $request->site_id;
        $town->site_name = $request->site_name;
        $town->site_type_id = $request->site_type_id;
        $town->address = $request->address;
        $town->nearest_town = $request->nearest_town;
        $town->county_id = $request->county_id;
        $town->department = $request->department;
        $town->landline = $request->landline;
        $town->mobile = $request->mobile;
        $town->email = $request->email;
        $town->in_charge = $request->in_charge;
        $town->latitude = $request->latitude;
        $town->longitude = $request->longitude;
        $town->user_id = Auth::user()->id;
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

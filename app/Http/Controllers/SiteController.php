<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SiteRequest;
use App\Models\Facility;
use App\Models\SiteType;
use App\Models\SubCounty;
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
		return view('site.site.index', compact('sites'));
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
		//	Get all sub-counties
		$subCounties = SubCounty::lists('name', 'id');
		
		return view('site.site.create', compact('facilities','siteTypes', 'subCounties'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SiteRequest $request)
	{

		$site = new Site;
		$site->facility_id = $request->facility;
        $site->local_id = $request->local_id;
        $site->name = $request->name;
        $site->site_type_id = $request->site_type;
        $site->department = $request->department;
        $site->mobile = $request->mobile;
        $site->email = $request->email;
        $site->in_charge = $request->in_charge;
        $site->user_id = Auth::user()->id;;
        $site->save();


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
		//show the view and pass the $site to it
		return view('site.site.show', compact('site'));
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
		//	Get facilities
		$facilities = Facility::lists('name', 'id');
		//	Get facility
		$facility = $site->facility_id;
		//	Get all site types
		$siteTypes = SiteType::lists('name', 'id');
		//	Get initial facility type
		$siteType = $site->site_type_id;

        return view('site.site.edit', compact('site','facilities','facility', 'siteTypes','siteType'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(SiteRequest $request, $id)
	{

		$site = Site::findOrFail($id);
        $site->facility_id = $request->facility;
        $site->local_id = $request->site_id;
        $site->name = $request->name;
        $site->site_type_id = $request->site_type;
        $site->department = $request->department;
        $site->mobile = $request->mobile;
        $site->email = $request->email;
        $site->in_charge = $request->in_charge;
        $site->user_id = Auth::user()->id;
        $site->save();

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

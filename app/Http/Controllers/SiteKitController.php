<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\SiteKitRequest;
use App\Models\SiteKit;
use App\Models\Site;
use App\Models\TestKit;
use Response;
use Auth;

class SiteKitController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sitekits= SiteKit::all();
		
		return view('testKit.site.index', compact('sitekits'));
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{ 
		//	Get all sites
		$sites = Site::lists('name', 'id');
		//	Get all kits
		$kits = TestKit::lists('short_name', 'id');
		//	Stock availability
		$stocks = array(SiteKit::AVAILABLE=>'Available', SiteKit::NOTAVAILABLE=>'Not Available');
		return view('testKit.site.create', compact ('sites', 'kits', 'stocks'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SiteKitRequest $request)
	{
		$sitekit = new SiteKit;
        $sitekit->kit_id = $request->kit;
        $sitekit->site_id = $request->site;
        $sitekit->lot_no = $request->lot_no;
		$sitekit->expiry_date = $request->expiry_date;
		$sitekit->comments = $request->comments;
		$sitekit->stock_available = $request->stock_available;
        $sitekit->user_id = Auth::user()->id;
        $sitekit->save();

        return redirect('siteKit')->with('message', 'Site kit saved successfully.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a sitekit
		$sitekit = SiteKit::find($id);
		//show the view and pass the $town to it
		return view('testKit.site.show', compact('sitekit'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get sitekit
		$siteKit = siteKit::find($id);
		//	Get all sites
		$sites = Site::lists('name', 'id');
		//	Get specific site
		$site = $siteKit->site_id;
		//	Get all kits
		$kits = TestKit::lists('short_name', 'id');
		//	Get specific kit
		$kit = $siteKit->kit_id;
		//	Stock availability
		$stocks = array(SiteKit::AVAILABLE=>'Available', SiteKit::NOTAVAILABLE=>'Not Available');
		//	Get specific stock availability
		$stock = $siteKit->stock_available;

        return view('testKit.site.edit', compact('siteKit', 'sites', 'site', 'kits', 'kit', 'stocks', 'stock'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(SiteKitRequest $request, $id)
	{
		
		$sitekit = SiteKit::findOrFail($id);
        $sitekit->kit_id = $request->kit;
        $sitekit->site_id = $request->site;
        $sitekit->lot_no = $request->lot_no;
		$sitekit->expiry_date = $request->expiry_date;
		$sitekit->comments = $request->comments;
		$sitekit->stock_available = $request->stock_available;
        $sitekit->user_id = Auth::user()->id;
        $sitekit->save();

        return redirect('siteKit')->with('message', 'Site kit updated successfully.');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		$sitekit= SiteKit::find($id);
		$sitekit->delete();
		return redirect('siteKit')->with('message', 'Site kit deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}
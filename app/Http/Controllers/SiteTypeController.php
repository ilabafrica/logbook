<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\SiteTypeRequest;
use App\Models\SiteType;
use Response;
use Auth;

class SiteTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all facility types
		$siteTypes = SiteType::all();
		return view('management.site.type.index', compact('siteTypes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('management.site.type.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SiteTypeRequest $request)
	{
		$siteType = new SiteType;
        $siteType->name = $request->name;
        $siteType->description = $request->description;
        $siteType->user_id = Auth::user()->id;;
        $siteType->save();

        return redirect('siteType')->with('message', 'Site type created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Site type
		$siteType = SiteType::find($id);
		//show the view and pass the $facilityType to it
		return view('management.site.type.show', compact('siteType'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$siteType = SiteType::find($id);

        return view('management.site.type.edit', compact('siteType'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(SiteTypeRequest $request, $id)
	{
		$siteType = SiteType::findOrFail($id);;
        $siteType->name = $request->name;
        $siteType->description = $request->description;
        $siteType->user_id = Auth::user()->id;;
        $siteType->save();

        return redirect('siteType')->with('message', 'Site type updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$siteType= siteType::find($id);
		$siteType->delete();
		return redirect('siteType')->with('message', 'Site Type deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}

}

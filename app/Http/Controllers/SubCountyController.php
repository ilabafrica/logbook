<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\SubCountyRequest;
use App\Models\SubCounty;
use App\Models\County;
use Response;
use Auth;

class SubCountyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all sub-counties
		$subCounties = SubCounty::all();
		
		return view('mfl.subCounty.index', compact('subCounties'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all counties for select list
		$counties = County::lists('name', 'id');
		return view('mfl.subCounty.create', compact('counties'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SubCountyRequest $request)
	{
		$subCounty = new SubCounty;
        $subCounty->name = $request->name;
        $subCounty->county_id = $request->county_id;
        $subCounty->user_id = Auth::user()->id;;
        $subCounty->save();

        return redirect('subCounty')->with('message', 'Sub-County created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a subCounty
		$subCounty = SubCounty::find($id);
		//show the view and pass the $subCounty to it
		return view('mfl.subCounty.show', compact('subCounty'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get subCounty
		$subCounty = SubCounty::find($id);
		//	Get all counties
		$counties = County::lists('name', 'id');
		//	Get initially selected county
		$county = $subCounty->county_id;

        return view('mfl.subCounty.edit', compact('subCounty', 'counties', 'county'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(SubCountyRequest $request, $id)
	{
		$subCounty = SubCounty::findOrFail($id);;
        $subCounty->name = $request->name;
        $subCounty->county_id = $request->county_id;
        $subCounty->user_id = Auth::user()->id;;
        $subCounty->save();

        return redirect('subCounty')->with('message', 'Sub-County updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$subCounty= SubCounty::find($id);
		$subCounty->delete();
		return redirect('subCounty')->with('message', 'Sub-County deleted successfully.');
	}

	public function destroy($id)
	{
		//
	}

}

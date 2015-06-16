<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\ConstituencyRequest;
use App\Models\Constituency;
use App\Models\County;
use Response;
use Auth;

class ConstituencyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all onstituencies
		$constituencies = Constituency::all();
		
		return view('mfl.constituency.index', compact('constituencies'));
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
		return view('mfl.constituency.create', compact('counties'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ConstituencyRequest $request)
	{
		$constituency = new Constituency;
        $constituency->name = $request->name;
        $constituency->county_id = $request->county_id;
        $constituency->user_id = Auth::user()->id;;
        $constituency->save();

        return redirect('constituency')->with('message', 'Constituency created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a constituency
		$constituency = Constituency::find($id);
		//show the view and pass the $constituency to it
		return view('mfl.constituency.show', compact('constituency'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get constituency
		$constituency = Constituency::find($id);
		//	Get all counties
		$counties = County::lists('name', 'id');
		//	Get initially selected county
		$county = $constituency->county_id;

        return view('mfl.constituency.edit', compact('constituency', 'counties', 'county'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(ConstituencyRequest $request, $id)
	{
		$constituency = Constituency::findOrFail($id);;
        $constituency->name = $request->name;
        $constituency->county_id = $request->county_id;
        $constituency->user_id = Auth::user()->id;;
        $constituency->save();

        return redirect('constituency')->with('message', 'Constituency updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$constituency= Constituency::find($id);
		$constituency->delete();
		return redirect('constituency')->with('message', 'Constituency deleted successfully.');
	}

	public function destroy($id)
	{
		//
	}

}

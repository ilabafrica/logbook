<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\CountyRequest;
use App\Models\County;
use Response;
use Auth;
use Input;

class CountyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all counties
		$counties = County::all();
		return view('mfl.county.index', compact('counties'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('mfl.county.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CountyRequest $request)
	{
		$county = new County;
        $county->name = $request->name;
        $county->hq = $request->hq;
        $county->user_id = Auth::user()->id;;
        $county->save();

        return redirect('county')->with('message', 'County created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a County
		$county = County::find($id);
		//show the view and pass the $county to it
		return view('mfl.county.show', compact('county'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$county = County::find($id);

        return view('mfl.county.edit', compact('county'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(CountyRequest $request, $id)
	{
		$county = County::findOrFail($id);;
        $county->name = $request->name;
        $county->hq = $request->hq;
        $county->user_id = Auth::user()->id;;
        $county->save();

        return redirect('county')->with('message', 'County updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function delete($id)
	{
		$county= County::find($id);
		$county->delete();
		return redirect('county')->with('message', 'County deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
	/**
	*	Function to return sub-counties of a particular county to fill sub-counties dropdown
	*/
	public function dropdown(){
        $input = Input::get('county_id');
        $county = County::find($input);
        $subCounties = $county->subCounties;
        return json_encode($subCounties);
    }
}

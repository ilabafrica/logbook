<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\AgencyRequest;
use App\Models\Agency;
use Response;
use Auth;

class AgencyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all agency 
		$agencies = Agency::all();
		return view('agency.index', compact('agencies'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('agency.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(AgencyRequest $request)
	{
		$agency = new Agency;
        $agency->name = $request->name;
        $agency->description = $request->description;
        $agency->user_id = Auth::user()->id;;
        $agency->save();

        return redirect('agency')->with('message', 'Agency created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show agency 		
		$agency = Agency::find($id);
		//show the view and pass the $agency to it
		return view('agency.show', compact('agency'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$agency = agency::find($id);

        return view('agency.edit', compact('agency'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(AgencyRequest $request, $id)
	{
		$agency = Agency::findOrFail($id);;
        $agency->name = $request->name;
        $agency->description = $request->description;
        $agency->user_id = Auth::user()->id;;
        $agency->save();

        return redirect('agency')->with('message', 'Agency updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$agency= Agency::find($id);
		$agency->delete();
		return redirect('agency')->with('message', 'Agency deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}

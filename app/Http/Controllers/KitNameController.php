<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\KitNameRequest;
use App\Models\KitName;
use Response;
use Auth;

class KitNameController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all facility types
		$kitNames = KitName::all();
		return view('management.assigntestkit.kitname.index', compact('kitNames'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('management.assigntestkit.kitname.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(KitNameRequest $request)
	{
		$kitName = new KitName;
        $kitName->name = $request->name;
        $kitName->description = $request->description;
        $kitName->user_id = Auth::user()->id;
        $kitName->save();

        return redirect('kitName')->with('message', 'Site type created successfully.');
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
		$kitName = KitName::find($id);
		//show the view and pass the $facilityType to it
		return view('management.testkit.kitname.show', compact('kitName'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$kitName = KitName::find($id);

        return view('management.testkit.kitname.edit', compact('kitName'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(KitNameRequest $request, $id)
	{
		$kitName = KitName::findOrFail($id);;
        $kitName->name = $request->name;
        $kitName->description = $request->description;
        $kitName->user_id = Auth::user()->id;
        $kitName->save();

        return redirect('kitName')->with('message', 'Kit name updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$kitName= KitName::find($id);
		$kitName->delete();
		return redirect('kitName')->with('message', 'Site Type deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}

}

<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\TestKitRequest;
use App\Models\TestKit;
use Response;
use Auth;

class TestKitController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$testKits = TestKit::all();
		
		return view('kit.index', compact('testKits'));
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{ 
		return view('kit.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TestKitRequest $request)
	{

		$testKit = new TestKit;
        $testKit->name = $request->name;
        $testKit->description = $request->description;
        $testKit->user_id = Auth::user()->id;
        $testKit->save();

        return redirect('testKit')->with('message', 'Test kit saved successfully.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a testkit
		$testKit = TestKit::find($id);
		//show the view and pass the $town to it
		return view('kit.show', compact('testKit'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get testkit
		$testKit = TestKit::find($id);
		
        return view('kit.edit', compact('testKit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(TestKitRequest $request, $id)
	{
		
		$testKit = TestKit::findOrFail($id);
        $testKit->name = $request->name;
        $testKit->description = $request->description;
        $testKit->user_id = Auth::user()->id;
        $testKit->save();

        return redirect('testKit')->with('message', 'Test kit updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		$testKit= TestKit::find($id);
		$testKit->delete();
		return redirect('testKit')->with('message', 'Testkit deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
	public function import()
	{
		//	show the view 
		return view('kit.import');
	}
}

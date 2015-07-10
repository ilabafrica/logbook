<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\TestKitRequest;
use App\Models\TestKit;
use App\Models\Agency;
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
		$testkits= TestKit::all();
		
		return view('testKit.kit.index', compact('testkits'));
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{ 
		//	Get all agencies
		$agencies = Agency::lists('name', 'id');
		return view('testKit.kit.create', compact ('agencies'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TestKitRequest $request)
	{
		$testkit = new TestKit;
        $testkit->full_name = $request->full_name;
        $testkit->short_name = $request->short_name;
        $testkit->manufacturer = $request->manufacturer;
		$testkit->approval_status = $request->approval_status;
		$testkit->approval_agency_id = $request->approval_agency;
		$testkit->incountry_approval = $request->incountry_approval;
        $testkit->user_id = Auth::user()->id;
        $testkit->save();

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
		$testkit = TestKit::find($id);
		//show the view and pass the $town to it
		return view('testKit.kit.show', compact('testkit'));
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
		//	Get all agencies
		$agencies = Agency::lists('name', 'id');
		//	Get initial facility type
		$agency = $testKit->approval_agency_id;		

        return view('testKit.kit.edit', compact('testKit', 'agencies', 'agency'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(TestKitRequest $request, $id)
	{
		
		$testkit = TestKit::findOrFail($id);
        $testkit->full_name = $request->full_name;
        $testkit->short_name = $request->short_name;
        $testkit->manufacturer = $request->manufacturer;
		$testkit->approval_status = $request->approval_status;
		$testkit->approval_agency_id = $request->approval_agency;
		$testkit->incountry_approval = $request->incountry_approval;
        $testkit->user_id = Auth::user()->id;
        $testkit->save();

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
		$testkit= TestKit::find($id);
		$testkit->delete();
		return redirect('testKit')->with('message', 'Testkit deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
	public function import()
	{
		//	show the view 
		return view('testKit.kit.import');
	}
}
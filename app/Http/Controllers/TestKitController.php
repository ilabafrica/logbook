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
		
		return view('admin.testkit.index', compact('testkits'));
		
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
		return view('admin.testkit.create', compact ('agencies'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TestKitRequest $request)
	{
		$county = new TestKit;
        $county->full_testkit_name = $request->full_testkit_name;
        $county->kit_name = $request->kit_name;
        $county->manufacturer = $request->manufacturer;
		$county->approval_status = $request->approval_status;
		$county->approval_agency_id = $request->approval_agency;
		$county->incountry_approval = $request->incountry_approval;
        //$county->user_id = Auth::user()->id;
        $county->save();

        return redirect('testkit')->with('message', 'Test kit saved successfully.');
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
		return view('admin.testkit.show', compact('testkit'));
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
		$testkit = TestKit::find($id);
		//	Get all agencies
		$agencies = Agency::lists('name', 'id');
		//	Get initial facility type
		$agency = $testkit->approval_agency_id;
		

        return view('admin.testkit.edit', compact('testkit', 'agencies', 'agency'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(TestKitRequest $request, $id)
	{
		
		$county = TestKit::findOrFail($id);
        $county->full_testkit_name = $request->full_testkit_name;
        $county->kit_name = $request->kit_name;
        $county->manufacturer = $request->manufacturer;
		$county->approval_status = $request->approval_status;
		$county->approval_agency_id = $request->approval_agency;
		$county->incountry_approval = $request->incountry_approval;
        $county->user_id = Auth::user()->id;
        $county->save();

        return redirect('testkit')->with('message', 'Test kit updated successfully.');

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
		return redirect('testkit')->with('message', 'Testkit deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}

}

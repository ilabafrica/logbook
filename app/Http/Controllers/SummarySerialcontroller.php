<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SerialRequest;
use App\Models\Facility;
use App\Models\TestKit;
use App\Models\Site;
use App\Models\Serial;
use Response;
use Auth;

class SummarySerialController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{	$facilities= Facility::lists('name', 'id');
		$serials= Serial::all();
		$testkits= TestKit::lists('full_testkit_name', 'id');
		$sites= Site::lists('site_name', 'id');
		
		
		return view('dataentry.summaryserial', compact('testkits', 'sites', 'serials', 'facilities'));
		//return view('dataentry.serial', compact('testkits', 'sites'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SerialRequest $request)
	{
		
		


        

        
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a serial
		$serial = Serial::find($id);
	
		return view('dataentry.showserial', compact('serial'));

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(SerialRequest $request, $id)
	{
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		//$site= Site::find($id);
		//$site->delete();
		//return redirect('site')->with('message', 'Site deleted successfully.');
	}

	public function destroy($id)
	{
		//
	}

}

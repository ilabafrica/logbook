<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SerialRequest;
use App\Models\TestKit;
use App\Models\Site;
use App\Models\Serial;
use Response;
use Auth;

class SerialController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$testkits= TestKit::lists('full_testkit_name', 'id');
		$sites= Site::lists('site_name', 'id');
		$serials= Serial::all();
		dd($serials);
		return view('dataentry.serial', compact('testkits', 'sites', 'serials'));
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
		$town = new Serial;
		$town->test_site_id = $request->test_site;
        $town->book_no = $request->book_no;
        $town->page_no = $request->page_no;
        $town->start_date = $request->start_date;
        $town->end_date = $request->end_date;
        $town->test_kit1_id = $request->test_kit1;
        $town->test_kit2_id = $request->test_kit2;
        $town->test_kit3_id = $request->test_kit3;
        $town->test_kit1R = $request->test_kit1R;
        $town->test_kit1NR = $request->test_kit1NR;
        $town->test_kit1Inv = $request->test_kit1Inv;
        $town->test_kit2R = $request->test_kit2R;
        $town->test_kit2NR = $request->test_kit2NR;
        $town->test_kit2Inv = $request->test_kit2Inv;
        $town->test_kit3R = $request->test_kit3R;
        $town->test_kit3NR = $request->test_kit3NR;
        $town->test_kit3Inv = $request->test_kit3Inv;
        $town->positive = $request->positive;
        $town->negative = $request->negative;
        $town->indeterminate = $request->indeterminate;
        //$town->user_id = Auth::user()->id;
        $town->save();

        $testkits= TestKit::lists('full_testkit_name', 'id');
		$sites= Site::lists('site_name', 'id');

		return view('dataentry.serial', compact('testkits', 'sites'))->with('message', 'Successfully Saved.');
		


        

        
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a facility
		$site = Site::find($id);
		//show the view and pass the $town to it
		return view('dataentry.serial', compact('site'));
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
		$town = Site::findOrFail($id);;
        $town->code = $request->code;
        $town->name = $request->name;
        $town->facility_type_id = $request->facility_type;
        $town->facility_owner_id = $request->facility_owner;
        $town->reporting_to = $request->reporting_to;
        $town->nearest_town = $request->nearest_town;
        $town->landline = $request->landline;
        $town->mobile = $request->mobile;
        $town->email = $request->email;
        $town->address = $request->address;
        $town->in_charge = $request->in_charge;
        $town->operational_status = $request->operational_status;
         $town->latitude = $request->latitude;
        $town->longitude = $request->longitude;
        $town->user_id = Auth::user()->id;;
        $town->save();

        return redirect('site')->with('message', 'Site updated successfully.');
       
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		$site= Site::find($id);
		$site->delete();
		return redirect('site')->with('message', 'Site deleted successfully.');
	}

	public function destroy($id)
	{
		//
	}

}

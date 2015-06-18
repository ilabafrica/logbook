<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SerialRequest;
use App\Models\AssignTestKit;
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
	public function index($id)
	{
		$assignedtestkits = [];
		$assigneds= AssignTestKit::with('testkit')->get();
		foreach ($assigneds as $key => $assignedtestkit) {
			$assignedtestkits[$assignedtestkit->id] = $assignedtestkit->testkit->kit_name;
		}
		$sites= Site::where('facility_id', $id)->lists('site_name', 'id');
		
		
		return view('dataentry.serial', compact('assignedtestkits', 'sites'));
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
        $town->user_id = Auth::user()->id;
        $town->save();

        $assignedtestkits= AssignTestKit::lists('kit_name_id', 'id');
		$sites= Site::lists('site_name', 'id');


		return view('dataentry.serial', compact('assignedtestkits', 'sites'))->with('message', 'Successfully Saved.');
		


        

        
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
		//show the view and pass the $town to it
		return view('dataentry.serial', compact('serials'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		//	Get serial
		$serial = Serial::find($id);
		$assignedtestkits= AssignTestKit::lists('kit_name_id', 'id');
		$assignedtestkit1=$serial->test_kit1_id;
		$assignedtestkit2=$serial->test_kit2_id;
		$assignedtestkit3=$serial->test_kit3_id;
		$sites= Site::lists('site_name', 'id');
		$site= $serial->test_site_id;
		 return view('dataentry.editserial', compact('serial', 'assignedtestkits','assignedtestkit1','assignedtestkit2','assignedtestkit3', 'sites', 'site'));
	

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(SerialRequest $request, $id)
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
        $town->user_id = Auth::user()->id;
        $town->save();

        return redirect('result')->with('message', 'Updated successfully.');
       
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

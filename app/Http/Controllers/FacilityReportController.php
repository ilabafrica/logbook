<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Htc;
use Response;
use Auth;
use Lang;
use Input;

class FacilityReportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		  $facility = Facility::find(2);
        //    Get data for the sites in the facility
        $sites = $facility->sites;
        $testKits = array(['id' => Htc::TESTKIT1, 'name' => Lang::choice('messages.s-kit-1', 1)], ['id' => Htc::TESTKIT2, 'name' => Lang::choice('messages.s-kit-2', 1)], ['id' => Htc::TESTKIT3, 'name' => Lang::choice('messages.s-kit-3', 1)]);
        //    Create color variable
        $class = NULL;
        
        return view('report.facilityreport.index', compact('facility', 'sites','testKits', 'class'));


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
	public function store()
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
	public function update()
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
		
	}

	public function destroy($id)
	{
		//
	}

}

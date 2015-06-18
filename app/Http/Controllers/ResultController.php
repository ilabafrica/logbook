<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResultRequest;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\County;
use App\Models\Serial;
use App\Models\Parallel;
use Response;
use Auth;

class ResultController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$facilities= Facility::lists('name', 'id');
		$serials= Serial::all();
		$parallels= Parallel::all();
		return view('management.result.index', compact('facilities', 'serials', 'parallels'));

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
	public function store(ResultRequest $request)
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
	public function update(ResultRequest $request, $id)
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
		
	}

}

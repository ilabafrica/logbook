<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SummaryParallelRequest;
use App\Models\Facility;
use App\Models\TestKit;
use App\Models\Site;
use App\Models\Parallel;
use Response;
use Auth;

class SummaryParallelController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(SummaryParallelRequest $request)
	{	
		$siteId = $request->input('site');

		$facilities= Facility::lists('name', 'id');
		$parallels= Parallel::where('test_site_id','=',$siteId);
		$testkits= TestKit::lists('full_testkit_name', 'id');
		$sites= Site::lists('site_name', 'id');
		
		
		return view('dataentry.summaryparallel', compact('testkits', 'sites', 'parallels', 'facilities'));
		//return view('dataentry.parallel', compact('testkits', 'sites'));
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
	public function store(SummaryParallelRequest $request)
	{
				
		$siteId = $request->input('sites');
		$facilities= Facility::lists('name', 'id');
		$parallels= Parallel::where('test_site_id', $siteId)->get();
		//dd($parallels);
		$testkits= TestKit::lists('full_testkit_name', 'id');
		$sites= Site::lists('site_name', 'id');
				
		return view('dataentry.summaryparallel', compact('testkits', 'sites', 'parallels', 'facilities'));
		//return view('dataentry.parallel', compact('testkits', 'sites'));
     
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @rparalleleturn Response
	 */
	public function show($id)
	{
		//show a parallel
		$parallel = Parallel::find($id);
	
		return view('dataentry.showparallel', compact('parallel'));

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
	public function update(parallelRequest $request, $id)
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

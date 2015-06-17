<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AssignTestKitRequest;
//use App\Models\Facility;
use App\Models\TestKit;
use App\Models\Site;
use App\Models\AssignTestKit;
use Response;
use Auth;

class AssignTestKitController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all sites
		$assigntestkits = AssignTestKit::all();
		return view('management.assigntestkit.index', compact('assigntestkits'));
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all facility types
		$testkits = TestKit::lists('kit_name', 'id');
		//	Get all test sites
		$sites = Site::lists('site_name', 'id');
		
		return view('management.assigntestkit.create', compact('testkits','sites'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(AssignTestKitRequest $request)
	{
		$town = new AssignTestKit;
		$town->site_name_id = $request->site_name;
        $town->kit_name_id = $request->kit_name;
        $town->lot_no = $request->lot_no;
        $town->expiry_date = $request->expiry_date;
        $town->comments = $request->comments;
        $town->stock_avl = $request->stock_avl;
        $town->user_id = Auth::user()->id;
        $town->save();

        return redirect('assigntestkit')->with('message', 'Test Kit assigned successfully.');
        

        
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
		$assigntestkit = AssignTestKit::find($id);
		//show the view and pass the $town to it
		return view('management.assigntestkit.show', compact('assigntestkit'));
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
		$assigntestkit = AssignTestKit::find($id);

		//	Get all testkits
		$testkits = TestKit::lists('kit_name', 'id');
		//	Get testkit
		$testkit = $assigntestkit->kit_name_id;
		//	Get all sites
		$sites = Site::lists('site_name', 'id');
		//	Get initial site
		$site = $sites->site_name_id;
		
		

        return view('management.assigntestkit.edit', compact('assigntestkit', 'testkits', 'testkit','sites', 'site'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(AssignTestKitRequest $request, $id)
	{
		$town = Site::findOrFail($id);;
        $town->site_name_id = $request->site_name;
        $town->kit_name_id = $request->kit_name;
        $town->lot_no = $request->lot_no;
        $town->expiry_date = $request->expiry_date;
        $town->comments = $request->comments;
        $town->stock_avl = $request->stock_avl;
        $town->user_id = Auth::user()->id;
        $town->save();

        return redirect('assigntestkit')->with('message', 'Testkit updated successfully.');
       
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		$site= AssignTestKit::find($id);
		$site->delete();
		return redirect('assigntestkit')->with('message', 'Site deleted successfully.');
	}

	public function destroy($id)
	{
		//
	}

}

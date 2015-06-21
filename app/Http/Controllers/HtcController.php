<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\HtcRequest;

use App\Models\Htc;
use App\Models\HtcData;
use App\Models\Facility;
use App\Models\SiteKit;
use Response;
use Auth;
use Lang;
use Input;

class HtcController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		//	Get faility
		$facility = Facility::find($id);
		//	Get data for the sites in the facility
		$sites = $facility->sites;
		return view('htc.index', compact('facility', 'sites'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		//	Get faility
		$facility = Facility::find($id);
		//	Get data for the sites in the facility
		$sites = $facility->sites->lists('name', 'id');
		//	Create array for testkits
		$testKits = array(['id' => Htc::TESTKIT1, 'name' => Lang::choice('messages.s-kit-1', 1)], ['id' => Htc::TESTKIT2, 'name' => Lang::choice('messages.s-kit-2', 1)], ['id' => Htc::TESTKIT3, 'name' => Lang::choice('messages.s-kit-3', 1)]);
		//	Create color variable
		$color = NULL;
		//	Get site test kits
		$skits = SiteKit::lists('site_id', 'id');
		return view('htc.create', compact('facility', 'sites', 'testKits', 'color', 'skits'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$htc = new Htc;
        $htc->site_id = Input::get('site');
        $htc->book_no = Input::get('book_no');
        $htc->page_no = Input::get('page_no');
        $htc->start_date = Input::get('start_date');
        $htc->end_date = Input::get('end_date');
        $htc->positive = Input::get('positive');
        $htc->negative = Input::get('negative');
        $htc->indeterminate = Input::get('intermediate');
        $htc->user_id = Auth::user()->id;

        try{
			$htc->save();
			$testKits = array(Htc::TESTKIT1, Htc::TESTKIT2, Htc::TESTKIT3);
			for($i = 1; $i <= count($testKits); $i++){
				$htcData = new HtcData;
				$htcData->htc_id = $htc->id;
				$htcData->site_test_kit_id = Input::get('test_kit_'.$i);
				$htcData->reactive = Input::get('r_'.$i);
				$htcData->non_reactive = Input::get('nr_'.$i);
				$htcData->invalid = Input::get('inv_'.$i);
				$htcData->test_kit_no = $i;
				$htcData->save();
			}
			return redirect('htc')->with('message', 'Htc created successfully.');
		}catch(QueryException $e){
			Log::error($e);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show agency 		
		$agency = Agency::find($id);
		//show the view and pass the $agency to it
		return view('agency.show', compact('agency'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$agency = agency::find($id);

        return view('agency.edit', compact('agency'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(AgencyRequest $request, $id)
	{
		$agency = Agency::findOrFail($id);;
        $agency->name = $request->name;
        $agency->description = $request->description;
        $agency->user_id = Auth::user()->id;;
        $agency->save();

        return redirect('agency')->with('message', 'Agency updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$agency= Agency::find($id);
		$agency->delete();
		return redirect('agency')->with('message', 'Agency deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}

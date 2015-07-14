<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\ChecklistRequest;
use App\Models\Checklist;
use Response;
use Auth;
use Session;

class ChecklistController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all checklists
		$checklists = Checklist::all();
		return view('checklist.index', compact('checklists'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('checklist.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ChecklistRequest $request)
	{
		$checklist = new Checklist;
        $checklist->name = $request->name;
        $checklist->description = $request->description;
        $checklist->user_id = Auth::user()->id;
        try{
			$checklist->save();
			$url = session('SOURCE_URL');

        	return redirect()->to($url)->with('message', 'Checklist created successfully.')->with('active_checklist', $checklist ->id);
		}
		catch(QueryException $e){
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
		//show a Checklist
		$checklist = Checklist::find($id);
		//show the view and pass the $checklist to it
		return view('checklist.show', compact('checklist'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$checklist = Checklist::find($id);

        return view('checklist.edit', compact('checklist'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(ChecklistRequest $request, $id)
	{
		$checklist = Checklist::findOrFail($id);;
        $checklist->name = $request->name;
        $checklist->description = $request->description;
        $checklist->user_id = Auth::user()->id;;
        try{
			$checklist->save();
			$url = session('SOURCE_URL');

        	return redirect()->to($url)->with('message', 'Checklist created successfully.')->with('active_checklist', $checklist ->id);
		}
		catch(QueryException $e){
			Log::error($e);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$checklist= Checklist::find($id);
		$checklist->delete();
		return redirect('checklist')->with('message', 'checklist deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}

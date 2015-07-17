<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\SectionRequest;
use App\Models\Checklist;
use App\Models\Section;
use Response;
use Auth;

class SectionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all sections
		$sections = Section::all();
		return view('section.index', compact('sections'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all checklists
		$checklists = Checklist::lists('name', 'id');
		return view('section.create', compact('checklists'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SectionRequest $request)
	{
		$section = new Section;
        $section->name = $request->name;
        $section->label = $request->label;
        $section->description = $request->description;
        $section->checklist_id = $request->checklist;
        $section->total_points = $request->total_points;
        $section->user_id = Auth::user()->id;
        try{
			$section->save();
			$url = session('SOURCE_URL');
        
        	return redirect()->to($url)->with('message', 'Section created successfully.')->with('active_section', $section ->id);
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
		//show a Section
		$section = Section::find($id);
		//show the view and pass the $section to it
		return view('section.show', compact('section'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$section = Section::find($id);
		$checklists = Checklist::lists('name', 'id');
		//	Get initial checklist
		$checklist = $section->checklist_id;
		
        return view('section.edit', compact('section', 'checklists', 'checklist'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(SectionRequest $request, $id)
	{
		$section = Section::findOrFail($id);;
        $section->name = $request->name;
        $section->label = $request->label;
        $section->description = $request->description;
        $section->checklist_id = $request->checklist;
        $section->total_points = $request->total_points;
        $section->user_id = Auth::user()->id;;

        try{
			$section->save();
			$url = session('SOURCE_URL');
        
        	return redirect()->to($url)->with('message', 'Section updated successfully.')->with('active_section', $section ->id);
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
		$section= Section::find($id);
		$section->delete();
		return redirect('section')->with('message', 'section deleted successfully.');
	}

	public function destroy($id)
	{
		//
	}
}

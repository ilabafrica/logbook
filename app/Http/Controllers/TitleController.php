<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\TitleRequest;
use App\Models\Title;
use Response;
use Auth;

class TitleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all titles
		$titles = Title::all();
		return view('mfl.title.index', compact('titles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('mfl.title.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TitleRequest $request)
	{
		$title = new Title;
        $title->name = $request->name;
        $title->description = $request->description;
        $title->user_id = Auth::user()->id;;
        $title->save();

        return redirect('title')->with('message', 'Title created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a title
		$title = Title::find($id);
		//show the view and pass the $title to it
		return view('mfl.title.show', compact('title'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$title = Title::find($id);

        return view('mfl.title.edit', compact('title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(TitleRequest $request, $id)
	{
		$title = Title::findOrFail($id);;
        $title->name = $request->name;
        $title->description = $request->description;
        $title->user_id = Auth::user()->id;;
        $title->save();

        return redirect('title')->with('message', 'Title updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$title= Title::find($id);
		$title->delete();
		return redirect('title')->with('message', 'Title deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}

}

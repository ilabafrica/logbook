<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\PtRequest;
use App\Models\Pt;
use Response;
use Auth;
use Lang;

class PtController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pts = Pt::all();
		
		return view('pt.index', compact('pts'));
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{ 
		return view('pt.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PtRequest $request)
	{

		$pt = new Pt;
        $pt->name = $request->name;
        $pt->description = $request->description;
        $pt->user_id = Auth::user()->id;
        $pt->save();

        return redirect('pt')->with('message', Lang::choice('messages.record-successfully-saved', 1));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Pt
		$pt = Pt::find($id);
		//show the view and pass the $town to it
		return view('pt.show', compact('pt'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get Pt
		$pt = Pt::find($id);
		
        return view('pt.edit', compact('pt'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(PtRequest $request, $id)
	{
		
		$pt = Pt::findOrFail($id);
        $pt->name = $request->name;
        $pt->description = $request->description;
        $pt->user_id = Auth::user()->id;
        $pt->save();

        return redirect('pt')->with('message', Lang::choice('messages.record-successfully-updated', 1));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

		public function delete($id)
	{
		$pt= Pt::find($id);
		$pt->delete();
		return redirect('pt')->with('message', Lang::choice('messages.record-successfully-deleted', 1));
	}
	public function destroy($id)
	{
		//
	}
}

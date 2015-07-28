<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\AffiliationRequest;
use App\Models\Affiliation;
use Response;
use Auth;
use Session;

class AffiliationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all affiliations
		$affiliations = Affiliation::all();
		return view('affiliation.index', compact('affiliations'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('affiliation.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(AffiliationRequest $request)
	{
		$affiliation = new Affiliation;
        $affiliation->name = $request->name;
        $affiliation->description = $request->description;
        $affiliation->user_id = Auth::user()->id;;
        $affiliation->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', 'Affiliation created successfully.')->with('active_affiliation', $affiliation ->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a affiliation
		$affiliation = Affiliation::find($id);
		//show the view and pass the $affiliation to it
		return view('affiliation.show', compact('affiliation'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$affiliation = Affiliation::find($id);

        return view('affiliation.edit', compact('affiliation'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(AffiliationRequest $request, $id)
	{
		$affiliation = Affiliation::findOrFail($id);;
        $affiliation->name = $request->name;
        $affiliation->description = $request->description;
        $affiliation->user_id = Auth::user()->id;;
        $affiliation->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', 'Affiliation updated successfully.')->with('active_affiliation', $affiliation ->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$affiliation= Affiliation::find($id);
		$affiliation->delete();
		return redirect('affiliation')->with('message', 'Affiliation deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}

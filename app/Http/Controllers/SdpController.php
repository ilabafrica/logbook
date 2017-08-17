<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\SdpRequest;
use App\Models\Sdp;
use Response;
use Auth;
use Session;
use Lang;

class SdpController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all facility types
		$sdps = Sdp::all();
		return view('sdp.index', compact('sdps'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('sdp.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SdpRequest $request)
	{
		$sdp = new Sdp;
        $sdp->name = $request->name;
        $sdp->description = $request->description;
        $sdp->user_id = Auth::user()->id;
        $sdp->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-saved', 1))->with('active_sdp', $sdp ->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Site type
		$sdp = Sdp::find($id);
		//show the view and pass the $facilityType to it
		return view('sdp.show', compact('sdp'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$sdp = Sdp::find($id);

        return view('sdp.edit', compact('sdp'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(SdpRequest $request, $id)
	{
		$sdp = Sdp::findOrFail($id);;
        $sdp->name = $request->name;
        $sdp->description = $request->description;
        $sdp->user_id = Auth::user()->id;;
        $sdp->save();
		$url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-updated', 1))->with('active_sdp', $sdp ->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$sdp= Sdp::find($id);
		$sdp->delete();
		return redirect('sdp')->with('message', Lang::choice('messages.record-successfully-deleted', 1));
	}
	public function destroy($id)
	{
		//
	}
}

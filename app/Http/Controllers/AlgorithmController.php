<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\AlgorithmRequest;
use App\Models\Algorithm;
use Response;
use Auth;
use Session;

class AlgorithmController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Algorithm
	 */
	public function index()
	{
		//	Get all algorithms
		$algorithms = Algorithm::all();
		return view('algorithm.index', compact('algorithms'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Algorithm
	 */
	public function create()
	{
		return view('algorithm.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Algorithm
	 */
	public function store(AlgorithmRequest $request)
	{
		$algorithm = new Algorithm;
        $algorithm->name = $request->name;
        $algorithm->description = $request->description;
        $algorithm->user_id = Auth::user()->id;
        $algorithm->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', 'Algorithm created successfully.')->with('active_algorithm', $algorithm ->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Algorithm
	 */
	public function show($id)
	{
		//show a algorithm
		$algorithm = Algorithm::find($id);
		//show the view and pass the $algorithm to it
		return view('algorithm.show', compact('algorithm'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Algorithm
	 */
	public function edit($id)
	{
		$algorithm = Algorithm::find($id);

        return view('algorithm.edit', compact('algorithm'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Algorithm
	 */
	public function update(AlgorithmRequest $request, $id)
	{
		$algorithm = Algorithm::findOrFail($id);;
        $algorithm->name = $request->name;
        $algorithm->description = $request->description;
        $algorithm->user_id = Auth::user()->id;
        $algorithm->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', 'Algorithm updated successfully.')->with('active_algorithm', $algorithm ->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Algorithm
	 */
	public function delete($id)
	{
		$algorithm= Algorithm::find($id);
		$algorithm->delete();
		return redirect('algorithm')->with('message', 'Algorithm deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}

<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ResponseRequest;
use App\Models\Answer;
use Response;
use Auth;
use Session;

class ResponseController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all answers
		$answers = Answer::all();
		return view('answer.index', compact('answers'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('answer.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ResponseRequest $request)
	{
		$answer = new Answer;
        $answer->name = $request->name;
        $answer->description = $request->description;
        $answer->user_id = Auth::user()->id;
        $answer->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', 'Answer created successfully.')->with('active_answer', $answer ->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Answer
		$answer = Answer::find($id);
		//show the view and pass the $answer to it
		return view('answer.show', compact('answer'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$answer = Answer::find($id);

        return view('answer.edit', compact('answer'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(ResponseRequest $request, $id)
	{
		$answer = Answer::findOrFail($id);;
        $answer->name = $request->name;
        $answer->description = $request->description;
        $answer->user_id = Auth::user()->id;
        $answer->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', 'Answer updated successfully.')->with('active_answer', $answer ->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$answer= Answer::find($id);
		$answer->delete();
		return redirect('answer')->with('message', 'Answer deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}

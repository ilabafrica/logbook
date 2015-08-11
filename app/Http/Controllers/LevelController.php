<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\LevelRequest;
use App\Models\Level;
use Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Session;

class LevelController extends Controller {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all Levels
		$levels = Level::all();
		return view('level.index', compact('levels'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('level.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(LevelRequest $request)
	{
		$level = new Level;
        $level->name = $request->name;
        $level->description = $request->description;
        $level->range_lower = $request->range_lower;
        $level->range_upper = $request->range_upper;
        $level->user_id = Auth::user()->id;
        $level->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', 'Level created successfully.')->with('active_level', $level ->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a Level
		$level = Level::find($id);
		//show the view and pass the $level to it
		return view('level.show', compact('level'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$level = Level::find($id);

        return view('level.edit', compact('level'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(LevelRequest $request, $id)
	{
		$level = Level::findOrFail($id);;
        $level->name = $request->name;
        $level->description = $request->description;
        $level->range_lower = $request->range_lower;
        $level->range_upper = $request->range_upper;
        $level->user_id = Auth::user()->id;
        $level->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', 'Level updated successfully.')->with('active_level', $level ->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$level= Level::find($id);
		$level->delete();
		return redirect('level')->with('message', 'Level deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}
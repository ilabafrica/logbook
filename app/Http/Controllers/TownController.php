?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\TownRequest;
use App\Models\Town;
use App\Models\Constituency;
use Response;
use Auth;

class TownController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all towns
		$towns = Town::all();
		return view('mfl.town.index', compact('towns'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all constituencies
		$constituencies = Constituency::lists('name', 'id');
		return view('mfl.town.create', compact('constituencies'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TownRequest $request)
	{
		$town = new Town;
        $town->name = $request->name;
        $town->constituency_id = $request->constituency_id;
        $town->postal_code = $request->postal_code;
        $town->user_id = Auth::user()->id;;
        $town->save();

        return redirect('town')->with('message', 'Town created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//show a town
		$town = Town::find($id);
		//show the view and pass the $town to it
		return view('mfl.town.show', compact('town'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get town
		$town = Town::find($id);
		//	Get all constituencies
		$constituencies = Constituency::lists('name', 'id');
		//	Get initially selected constituency
		$constituency = $town->constituency_id;

        return view('mfl.town.edit', compact('town', 'constituencies', 'constituency'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(TownRequest $request, $id)
	{
		$town = Town::findOrFail($id);;
        $town->name = $request->name;
        $town->constituency_id = $request->constituency_id;
        $town->postal_code = $request->postal_code;
        $town->user_id = Auth::user()->id;;
        $town->save();

        return redirect('town')->with('message', 'Town updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$town= Town::find($id);
		$town->delete();
		return redirect('town')->with('message', 'Town deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}

}

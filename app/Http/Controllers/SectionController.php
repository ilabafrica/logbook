?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\SectionRequest;
use App\Models\AuditType;
use App\Models\Section;
use App\Models\Note;
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
		return view('audit.section.index', compact('sections'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get all present sections
		$parents = Section::lists('name', 'id');
		//	Get all audit types
		$auditTypes = AuditType::lists('name', 'id');
		//	Get all notes
		$notes = Note::orderBy('name')->get();
		return view('audit.section.create', compact('parents', 'auditTypes', 'notes'));
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
        $section->audit_type_id = $request->audit_type_id;
        $section->total_points = $request->total_points;
        $section->order = $request->order;
        $section->user_id = Auth::user()->id;;
        try{
			$section->save();
			if($request->parent_id){
				$section->setParent(array($request->parent_id));
			}
			if($request->notes){
				$section->setNotes($request->notes);
			}
			//$url = Session::get('SOURCE_URL');
        
        	return redirect('section')->with('message', 'Audit Section created successfully.');
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
		//show a Audit Section
		$section = Section::find($id);
		//show the view and pass the $section to it
		return view('audit.section.show', compact('section'));
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
		//	Get all present sections
		$parents = Section::lists('name', 'id');
		//	Get initial parent id
		$parent = $section->parent_id;
		//	Get all audit types
		$auditTypes = AuditType::lists('name', 'id');
		//	Get initial audit type
		$auditType = $section->audit_type_id;
		//	Get all notes
		$notes = Note::orderBy('name', 'ASC')->get();
		//	Get initial order
		$order = $section->order;

        return view('audit.section.edit', compact('section', 'parents', 'parent', 'auditTypes', 'auditType', 'notes', 'order'));
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
        $section->audit_type_id = $request->audit_type_id;
        $section->total_points = $request->total_points;
        $section->order = $request->order;
        $section->user_id = Auth::user()->id;;

        try{
			$section->save();
			if($request->parent_id){
				$section->setParent(array($request->parent_id));
			}
			if($request->notes){
				$section->setNotes($request->notes);
			}
			//$url = Session::get('SOURCE_URL');
        
        	return redirect('section')->with('message', 'Audit Section updated successfully.');
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

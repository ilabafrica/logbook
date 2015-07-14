<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use App\Models\Section;
use App\Models\Answer;
use Response;
use Auth;
use Session;

class QuestionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all questions
		$questions = Question::all();
		return view('question.index', compact('questions'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//	Get parent questions
		$parents = Question::lists('name', 'id');
		//	Get all audit questions
		$sections = Section::lists('name', 'id');
		//	Get all answers
		$answers = Answer::orderBy('name')->get();
		//	question types
		$questionTypes = array(Question::CHOICE=>'Choice', Question::DATE=>'Date', Question::FIELD=>'Field', Question::TEXTAREA=>'Free Text');
		return view('question.create', compact('parents', 'sections', 'questionTypes', 'answers', 'notes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(QuestionRequest $request)
	{
		//	Get the values
		$question = new Question;
		$question->section_id = $request->section;
		$question->name = $request->name;
		$question->description = $request->description;
		$question->question_type = $request->question_type;
		$question->required = $request->required;
		$question->info = $request->info;
		$question->score = $request->score;
		$question->user_id = Auth::user()->id;
		try{
			$question->save();
			if($request->answers){
				$question->setAnswers($request->answers);
			}
			$url = session('SOURCE_URL');
        
        	return redirect()->to($url)->with('message', 'Question created successfully.')->with('active_question', $question ->id);
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
		//show an Audit question
		$question = Question::find($id);
		//show the view and pass the $Section to it
		return view('question.show', compact('question'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get audit question
		$question = Question::find($id);
		//	Get all audit question groups
		$sections = Section::lists('name', 'id');
		//	Get audit question group
		$section = $question->section_id;
		//	question types
		$questionTypes = array(Question::CHOICE=>'Choice', Question::DATE=>'Date', Question::FIELD=>'Field', Question::TEXTAREA=>'Free Text');
		//	Get question type
		$questionType = $question->question_type;
		//	Get all answers
		$answers = Answer::orderBy('name')->get();
		return view('question.edit', compact('question', 'sections', 'questionTypes', 'parent', 'section', 'questionType', 'answers'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(QuestionRequest $request, $id)
	{
		$question = Question::find($id);
		$question->section_id = $request->section;
		$question->name = $request->name;
		$question->description = $request->description;
		$question->question_type = $request->question_type;
		$question->info = $request->info;
		$question->score = $request->score;
		$question->user_id = Auth::user()->id;

		try{
			$question->save();
			if($request->answers){
				$question->setAnswers($request->answers);
			}
			$url = session('SOURCE_URL');
        
        	return redirect()->to($url)->with('message', 'Question updated successfully.')->with('active_question', $question ->id);
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
		$question= Question::find($id);
		$question->delete();
		return redirect('question')->with('message', 'Question deleted successfully.');
	}
	public function destroy($id)
	{
		//
	}
}

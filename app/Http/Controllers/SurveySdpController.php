<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Request;
use Response;
use Auth;
use Input;
use Lang;
use App;
use Excel;
use Jenssegers\Date\Date as Carbon;
use DB;

class SurveySdpController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		dd(Input::all());
		//	Get survey
		$surveysdp = SurveySdp::find($id);
		$surveySdp->sdp_id = Input::get($sdp);
		$surveySdp->save();
		//	Proceed to survey-questions
		foreach (Input::all() as $key => $value) 
		{
			if((stripos($key, 'token') !==FALSE))
				continue;
			else if((stripos($key, 'text') !==FALSE) || (stripos($key, 'radio') !==FALSE) || (stripos($key, 'field') !==FALSE) || (stripos($key, 'textarea') !==FALSE) || (stripos($key, 'checkbox') !==FALSE) || (stripos($key, 'select') !==FALSE)){
				$questionId = $this->strip($key);
				$sq = $surveySdp->sq($questionId);
				if($sq)
				{
					$sd = $sq->sd;
					$sd->answer = $value;
					$sd->save();
				}
				else
				{
					//	Create the survey-question
					$sq = new SurveyQuestion;
					$sq->survey_sdp_id = $surveySdp->id;
					$sq->question_id = $questionId;
					$sq->save();
					//	survey-data
					$sd = new SurveyData;
					$sd->survey_question_id = $sq->id;
					$sd->answer = $value;
					$sd->save();
				}
			}
		}
		//	Redirect
		$url = session('SOURCE_URL');
		return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-duplicated', 1));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}

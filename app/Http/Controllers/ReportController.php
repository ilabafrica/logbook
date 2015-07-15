<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\Facility;
use App\Models\Survey;
use App\Models\SurveyData;
use App\Models\Question;
use App\Models\Sdp;

use Illuminate\Http\Request;
use Lang;
use Input;

class ReportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		$facility = Facility::find(1);
		$sdps = array();
		$tests = array('Test-1', 'Test-2');
		$survey_ids = array();
		//	Get dates
		$from = Input::get('from');
		$to = Input::get('to');
		$surveys = Survey::where('facility_id', $facility->id)
						 ->where('checklist_id', $checklist->id);
						 if($from && $to)
						 	$surveys = $surveys->whereBetween('created_at', [$from, $to]);
						 $surveys = $surveys->get();
		foreach ($surveys as $survey) {
			array_push($sdps, $survey->sdp->name);
		}
		foreach ($surveys as $survey) {
			if(in_array($survey->sdp->name, $sdps))
				array_push($survey_ids, $survey->id);
		}
		$chart = "{
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '".$facility->name."'
		        },
		        xAxis: {
		            categories: ["."'".implode(',', $sdps)."'"."]
		        },
		        yAxis: {
		            title: {
		                text: '".Lang::choice('messages.percent-positive', 1)."'
		            }
		        },
		        series: [";
		        foreach ($tests as $test) {
		        	$chart.="{name:"."'".$test."'".", data:[";
		        		foreach ($sdps as $sdp) {
		        			$chart.=Sdp::find(Sdp::idByName($sdp))->positivePercent($test, $survey_ids);
		        		}
		        	$chart.="]},";
		        }
		        $chart.="],
		    }";
		return view('report.positive', compact('checklist', 'chart'));
	}

	/**
	 * percent agreement report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function agreement($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		return view('report.agreement', compact('checklist'));
	}

	/**
	 * Overall agreement report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function overall($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		return view('report.overall', compact('checklist'));
	}
	/**
	 * Invalid results report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function Invalid($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		return view('report.Invalid', compact('checklist'));
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
		//
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
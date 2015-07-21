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
		
		//	Define months array
		$monthNames = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
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
	/**
	* Get months: return months for time_created column when filter dates are set
	*/	
	public static function getMonths($from, $to){
		$today = "'".date("Y-m-d")."'";
		$year = date('Y');
		$surveys = Survey::select('created_at')->distinct();

		if(strtotime($from)===strtotime($today)){
			$surveys = $surveys->where('created_at', 'LIKE', $year.'%');
		}
		else
		{
			$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
			$surveys = $surveys->whereBetween('created_at', array($from, $toPlusOne));
		}

		$allDates = $surveys->lists('created_at');
		asort($allDates);
		$yearMonth = function($value){return strtotime(substr($value, 0, 7));};
		$allDates = array_map($yearMonth, $allDates);
		$allMonths = array_unique($allDates);
		$dates = array();

		foreach ($allMonths as $date) {
			$dateInfo = getdate($date);
			$dates[] = array('months' => $dateInfo['mon'], 'label' => substr($dateInfo['month'], 0, 3),
				'annum' => $dateInfo['year']);
		}

		return json_encode($dates);
	}
}
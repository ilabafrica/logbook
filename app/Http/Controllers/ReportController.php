<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\Facility;
use App\Models\Survey;
use App\Models\SurveyData;
use App\Models\Question;
use App\Models\Sdp;
use App\Models\QuestionResponse;
use App\Models\Section;
use App\Models\Answer;

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
		//	Retrieve HTC Lab Register
		$checklist = Checklist::find(Checklist::idByName('HTC Lab Register (MOH 362)'));
		//	Get sdps
		$sdps = $checklist->sdps();
		
		//	Define months array
		$monthNames = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		//	Get checklist
		//$checklist = Checklist::find($id);
		$facility = Facility::find(1);
		/*//$sdps = array();
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
		}*/
		$months = json_decode(self::getMonths($from = NULL, $to = NULL));
		$chart = "{
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '".$facility->name."'
		        },
		        xAxis: {
		            categories: [";
		            $count = count($months);
		            	foreach ($months as $month) {
		    				$chart.= "'".$month->label.' '.$month->annum;
		    				if($count==1)
		    					$chart.="' ";
		    				else
		    					$chart.="' ,";
		    				$count--;
		    			}
		            $chart.="]
		        },
		        yAxis: {
		            title: {
		                text: '".Lang::choice('messages.percent-positive', 1)."'
		            }
		        },
		        series: [";
		        $counts = count($sdps);
		        foreach ($sdps as $sdp) {
		        	$chart.="{name:"."'".Sdp::find($sdp->sdp_id)->name."'".", data:[";
	        		$counter = count($months);
	        		foreach ($months as $month) {
	        			$data = Sdp::find($sdp->sdp_id)->positivePercent();
	        			if($data==0){
            					$chart.= '0.00';
            					if($counter==1)
	            					$chart.="";
	            				else
	            					$chart.=",";
        				}
        				else{
            				$chart.= $data;

            				if($counter==1)
            					$chart.="";
            				else
            					$chart.=",";
        				}
            			$counter--;
            		}
            		$chart.="]";
	            	if($counts==1)
						$chart.="}";
					else
						$chart.="},";
					$counts--;
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
		//	Get sdps
		$sdps = $checklist->sdps();
		//	Get facility
		$facility = Facility::find(1);
		$months = json_decode(self::getMonths($from = NULL, $to = NULL));
		$chart = "{
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '".$facility->name."'
		        },
		        xAxis: {
		            categories: [";
		            $count = count($months);
		            	foreach ($months as $month) {
		    				$chart.= "'".$month->label.' '.$month->annum;
		    				if($count==1)
		    					$chart.="' ";
		    				else
		    					$chart.="' ,";
		    				$count--;
		    			}
		            $chart.="]
		        },
		        yAxis: {
		            title: {
		                text: '".Lang::choice('messages.percent-positiveAgr', 1)."'
		            }
		        },
		        series: [";
		        $counts = count($sdps);
		        foreach ($sdps as $sdp) {
		        	$chart.="{name:"."'".Sdp::find($sdp->sdp_id)->name."'".", data:[";
	        		$counter = count($months);
	        		foreach ($months as $month) {
	        			$data = Sdp::find($sdp->sdp_id)->positiveAgreement();
	        			if($data==0){
            					$chart.= '0.00';
            					if($counter==1)
	            					$chart.="";
	            				else
	            					$chart.=",";
        				}
        				else{
            				$chart.= $data;

            				if($counter==1)
            					$chart.="";
            				else
            					$chart.=",";
        				}
            			$counter--;
            		}
            		$chart.="]";
	            	if($counts==1)
						$chart.="}";
					else
						$chart.="},";
					$counts--;
		        }
		        $chart.="],
		    }";
		return view('report.agreement', compact('checklist', 'chart'));
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
		//	Get sdps
		$sdps = $checklist->sdps();
		//	Get facility
		$facility = Facility::find(1);
		$months = json_decode(self::getMonths($from = NULL, $to = NULL));
		$chart = "{
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '".$facility->name."'
		        },
		        xAxis: {
		            categories: [";
		            $count = count($months);
		            	foreach ($months as $month) {
		    				$chart.= "'".$month->label.' '.$month->annum;
		    				if($count==1)
		    					$chart.="' ";
		    				else
		    					$chart.="' ,";
		    				$count--;
		    			}
		            $chart.="]
		        },
		        yAxis: {
		            title: {
		                text: '".Lang::choice('messages.percent-overallAgr', 1)."'
		            }
		        },
		        series: [";
		        $counts = count($sdps);
		        foreach ($sdps as $sdp) {
		        	$chart.="{name:"."'".Sdp::find($sdp->sdp_id)->name."'".", data:[";
	        		$counter = count($months);
	        		foreach ($months as $month) {
	        			$data = Sdp::find($sdp->sdp_id)->overallAgreement();
	        			if($data==0){
            					$chart.= '0.00';
            					if($counter==1)
	            					$chart.="";
	            				else
	            					$chart.=",";
        				}
        				else{
            				$chart.= $data;

            				if($counter==1)
            					$chart.="";
            				else
            					$chart.=",";
        				}
            			$counter--;
            		}
            		$chart.="]";
	            	if($counts==1)
						$chart.="}";
					else
						$chart.="},";
					$counts--;
		        }
		        $chart.="],
		    }";
		return view('report.overall', compact('checklist', 'chart'));
	}/**
	 * Invalid results report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function invalid($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		return view('report.invalid', compact('checklist'));
	}
	/**
	 * M$E stacked percentages report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function me($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Get facility
		$facility = Facility::find(1);
		$categories = array();
		foreach ($checklist->sections as $section){
			if($section->isScorable())
				array_push($categories, $section->id);
		}
		//	Get distinct responses
		$options = QuestionResponse::join('questions', 'question_responses.question_id', '=', 'questions.id')
									->join('responses', 'question_responses.response_id', '=', 'responses.id')
									->join('sections', 'questions.section_id', '=', 'sections.id')
									->where('sections.checklist_id', $checklist->id)
									->whereNotNull('responses.score')
									->groupBy('response_id')
									->get();
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".$facility->name."'
	        },
	        xAxis: {
	            categories: [";
	            	foreach ($categories as $category) {
	            		$chart.="'".Section::find($category)->label."',";
	            	}
	            $chart.="]
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: '% Score'
	            }
	        },
	        tooltip: {
	            pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
	            shared: true
	        },
	        plotOptions: {
	            column: {
	                stacking: 'percent'
	            }
	        },
	        series: [";
	        	$counts = count($options);
		        foreach ($options as $option) {
		        	$chart.="{name:"."'".Answer::find($option->response_id)->name."'".", data:[";
	        		$counter = count($categories);
	        		foreach ($categories as $category) {
	        			$data = Answer::find($option->response_id)->column($category);
	        			if($data==0){
            					$chart.= '0.00';
            					if($counter==1)
	            					$chart.="";
	            				else
	            					$chart.=",";
        				}
        				else{
            				$chart.= $data;

            				if($counter==1)
            					$chart.="";
            				else
            					$chart.=",";
        				}
            			$counter--;
            		}
            		$chart.="]";
	            	if($counts==1)
						$chart.="}";
					else
						$chart.="},";
					$counts--;
		        }
		        $chart.="],
	    }";
		return view('report.mscolumn', compact('checklist', 'chart'));
	}
	/**
	 * SPI-RT spider chart report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function spirt($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Add scorable sections to array
		$categories = array();
		foreach ($checklist->sections as $section){
			if($section->isScorable())
				array_push($categories, $section);
		}
		$chart = "{

	        chart: {
	            polar: true,
	            type: 'line'
	        },

	        title: {
	            text: 'SPI-RT Scores Comparison',
	            x: -80
	        },

	        pane: {
	            size: '80%'
	        },

	        xAxis: {
	            categories: [";
	            	foreach ($categories as $category) {
	            		$chart.="'".$category->label."',";
	            	}
	            $chart.="],
	            tickmarkPlacement: 'on',
	            lineWidth: 0
	        },

	        yAxis: {
	            gridLineInterpolation: 'polygon',
	            lineWidth: 0,
	            min: 0
	        },

	        tooltip: {
	            shared: true,
	            pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y} %</b><br/>',
	        },

	        legend: {
	            align: 'right',
	            verticalAlign: 'top',
	            y: 70,
	            layout: 'vertical'
	        },

	        series: [{
	            name: 'Score',
	            data: [";
	            	foreach ($categories as $category) {
	   					$chart.=round($category->spider()*100/$category->total_points, 2).',';
	   				}
	   				$chart.="],
	            pointPlacement: 'on'
	        }]

	    }";
	    return view('report.spider', compact('checklist', 'chart'));
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
		$from = "'".date("Y-m-d")."'";
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
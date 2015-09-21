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
use App\Models\Level;
use App\Models\SubCounty;
use App\Models\County;

use Illuminate\Http\Request;
use Lang;
use Input;
use Auth;
use DateTime;
use Jenssegers\Date\Date as Carbon;

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
		
		//	Chart title
		$title = '';
		//	Get counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		//	Declare variables
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
			$site = Input::get('facility');
		if(Input::get('sub_county'))
			$sub_county = Input::get('sub_county');
		if(Input::get('county'))
			$jimbo = Input::get('county');
		//	Get sdps
		$sdps = array();
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL)
			{
				if($site!=NULL)
				{
					$title = Facility::find($site)->name;
					foreach (Facility::find($site)->surveys as $survey) 
					{
						foreach ($survey->sdps as $sdp) 
						{
							array_push($sdps, $sdp->sdp_id);
						}
					}
				}
				else
				{
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1);;
					foreach (SubCounty::find($sub_county)->facilities as $facility)
					{
						foreach ($facility->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
								array_push($sdps, $sdp->sdp_id);
							}
						}
					}
				}
			}
			else
			{
				$title = County::find($jimbo)->name.' '.Lang::choice('messages.county', 1);;
				foreach (County::find($jimbo)->subCounties as $subCounty)
				{
					foreach ($subCounty->facilities as $facility)
					{
						foreach ($facility->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
								array_push($sdps, $sdp->sdp_id);
							}
						}
					}
				}
			}
		}
		$sdps = array_unique($sdps);
		$from = Input::get('from');
		$to = Input::get('to');
		$months = json_decode(self::getMonths($from, $to));
		$chart = "{
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '".$title."'
		        },
			    subtitle: {
			        text:"; 
			        if($from==$to)
			        	$chart.="'".trans('messages.for-the-year').' '.date('Y')."'";
			        else
			        	$chart.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
			    $chart.="},
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
		        credits: {
				    enabled: false
				},
		        series: [";
		        $counts = count($sdps);
		        foreach ($sdps as $sdp) {
		        	$chart.="{name:"."'".Sdp::find($sdp)->name."'".", data:[";
	        		$counter = count($months);
	        		foreach ($months as $month) {
	        			$data = Sdp::find($sdp)->positivePercent($site, $sub_county, $jimbo, $month->annum, $month->months);
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
		return view('report.htc.positive', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site'));
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
		//	Chart title
		$title = '';
		//	Get counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		//	Declare variables
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
			$site = Input::get('facility');
		if(Input::get('sub_county'))
			$sub_county = Input::get('sub_county');
		if(Input::get('county'))
			$jimbo = Input::get('county');
		//	
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$jimbo = County::find(Auth::user()->tier->tier);
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$sub_county = SubCounty::find(Auth::user()->tier->tier);
		//	Get sdps
		$sdps = array();
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL)
			{
				if($site!=NULL)
				{
					$title = Facility::find($site)->name;
					foreach (Facility::find($site)->surveys as $survey) 
					{
						foreach ($survey->sdps as $sdp) 
						{
							array_push($sdps, $sdp->sdp_id);
						}
					}
				}
				else
				{
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1);;
					foreach (SubCounty::find($sub_county)->facilities as $facility)
					{
						foreach ($facility->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
								array_push($sdps, $sdp->sdp_id);
							}
						}
					}
				}
			}
			else
			{
				$title = County::find($jimbo)->name.' '.Lang::choice('messages.county', 1);;
				foreach (County::find($jimbo)->subCounties as $subCounty)
				{
					foreach ($subCounty->facilities as $facility)
					{
						foreach ($facility->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
								array_push($sdps, $sdp->sdp_id);
							}
						}
					}
				}
			}
		}
		$sdps = array_unique($sdps);
		$from = Input::get('from');
		$to = Input::get('to');
		$months = json_decode(self::getMonths($from, $to));
		$chart = "{
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '".$title."'
		        },
			    subtitle: {
			        text:"; 
			        if($from==$to)
			        	$chart.="'".trans('messages.for-the-year').' '.date('Y')."'";
			        else
			        	$chart.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
			    $chart.="},
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
		        credits: {
				    enabled: false
				},
		        series: [";
		        $counts = count($sdps);
		        foreach ($sdps as $sdp) {
		        	$chart.="{name:"."'".Sdp::find($sdp)->name."'".", data:[";
	        		$counter = count($months);
	        		foreach ($months as $month) {
	        			$data = Sdp::find($sdp)->positiveAgreement($site, $sub_county, $jimbo, $month->annum, $month->months);
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
		return view('report.htc.agreement', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site'));
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
		//	Chart title
		$title = '';
		//	Get counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		//	Declare variables
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
			$site = Input::get('facility');
		if(Input::get('sub_county'))
			$sub_county = Input::get('sub_county');
		if(Input::get('county'))
			$jimbo = Input::get('county');
		//	Get sdps
		$sdps = array();
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL)
			{
				if($site!=NULL)
				{
					$title = Facility::find($site)->name;
					foreach (Facility::find($site)->surveys as $survey) 
					{
						foreach ($survey->sdps as $sdp) 
						{
							array_push($sdps, $sdp->sdp_id);
						}
					}
				}
				else
				{
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1);;
					foreach (SubCounty::find($sub_county)->facilities as $facility)
					{
						foreach ($facility->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
								array_push($sdps, $sdp->sdp_id);
							}
						}
					}
				}
			}
			else
			{
				$title = County::find($jimbo)->name.' '.Lang::choice('messages.county', 1);;
				foreach (County::find($jimbo)->subCounties as $subCounty)
				{
					foreach ($subCounty->facilities as $facility)
					{
						foreach ($facility->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
								array_push($sdps, $sdp->sdp_id);
							}
						}
					}
				}
			}
		}
		$sdps = array_unique($sdps);
		$from = Input::get('from');
		$to = Input::get('to');
		$months = json_decode(self::getMonths($from, $to));
		$chart = "{
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '".$title."'
		        },
			    subtitle: {
			        text:"; 
			        if($from==$to)
			        	$chart.="'".trans('messages.for-the-year').' '.date('Y')."'";
			        else
			        	$chart.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
			    $chart.="},
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
		        credits: {
				    enabled: false
				},
		        series: [";
		        $counts = count($sdps);
		        foreach ($sdps as $sdp) {
		        	$chart.="{name:"."'".Sdp::find($sdp)->name."'".", data:[";
	        		$counter = count($months);
	        		foreach ($months as $month) {
	        			$data = Sdp::find($sdp)->overallAgreement($site, $sub_county, $jimbo, $month->annum, $month->months);
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
		return view('report.htc.overall', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site'));
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
		return view('report.htc.invalid', compact('checklist'));
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
		//	Chart title
		$title = '';
		//	Get counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		//	Declare variables
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
			$title = Facility::find($site)->name;
		}
		if(Input::get('sub_county'))
			$sub_county = Input::get('sub_county');
		if(Input::get('county'))
			$jimbo = Input::get('county');
		$categories = array();
		$options = array();
		foreach ($checklist->sections as $section) 
		{
			if($section->isScorable())
				array_push($categories, $section);
		}
		foreach ($categories as $category) {
			foreach ($category->questions as $question) 
			{
				if($question->answers->count()>0)
				{
					foreach ($question->answers as $answer) 
					{
						array_push($options, $answer->name);
					}
				}
			}
		}
		$options = array_unique($options);
		$from = Input::get('from');
		$to = Input::get('to');
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {

	            text: '".$title."'

	        },
		    subtitle: {
		        text:"; 
		        if($from==$to)
		        	$chart.="'".trans('messages.for-the-year').' '.date('Y')."'";
		        else
		        	$chart.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
		    $chart.="},
	        xAxis: {
	            categories: [";
	            	foreach ($categories as $category) {
	            		$chart.="'".$category->label."',";
	            	}
	            $chart.="]
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: '% Score'
	            }
	        },
	        credits: {
			    enabled: false
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
		        	$chart.="{colorByPoint: false, name:"."'".Answer::find(Answer::idByName($option))->name."'".", data:[";
	        		$counter = count($categories);
	        		foreach ($categories as $category) {
	        			$data = Answer::find(Answer::idByName($option))->column($category->id, $site, $from, $to);
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
            		$chart.="], color:"."'".$colors[$counts-1]."'";
	            	if($counts==1)
						$chart.="}";
					else
						$chart.="},";
					$counts--;
		        }
		        $chart.="],
	    }";
		return view('report.me.mscolumn', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site'));
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
		//	Chart title
		$title = '';
		//	Get counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		$to = Input::get('to');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		}
		elseif(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
		}
		//	Update chart title
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL)
			{
				if($site!=NULL)
				{
					$title = Facility::find($site)->name;
				}
				else
				{
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1);
				}
			}
			else
			{
				$title = County::find($jimbo)->name.' '.Lang::choice('messages.county', 1);
			}
		}
		else
		{
			$title = 'Kenya';
		}
		$chart = "{

	        chart: {
	            polar: true,
	            type: 'line'
	        },

	        title: {
	            text: 'SPI-RT Scores Comparison for $title',
	            x: -80
	        },	        
		    subtitle: {
		        text:"; 
		        if($from==$to)
		        	$chart.="'".trans('messages.for-the-year').' '.date('Y')."'";
		        else
		        	$chart.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
		    $chart.="},
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
	        credits: {
			    enabled: false
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
	   					$chart.=$category->spider($site, $sub_county, $jimbo, $from, $toPlusOne).',';
	   				}
	   				$chart.="],
	            pointPlacement: 'on'
	        }]

	    }";
	    //	Get data for table
	    $data = array();
	    foreach ($categories as $category)
	    {
	    	$data[$category->id] = $category->spider($site, $sub_county, $jimbo, $from, $toPlusOne);
	    }
	    return view('report.spirt.spider', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'categories', 'data', 'title', 'from', 'to', 'jimbo', 'sub_county', 'site'));
	}
	/**
	 * Show the table for current stage of sites implementing RTQII priority activities in Country X (percentage of sites)..
	 *
	 * @return Response
	 */
	public function chart()
	{
		//	Get counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = SubCounty::lists('name', 'id');
		$jimbo = NULL;
		$sub_county = NULL;
		if(Input::get('county') && !(Input::get('sub_county')))
			$jimbo = Input::get('county');
		else if(Input::get('county') && !(Input::get('sub_county')))
			$sub_county = Input::get('sub_county');
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('M & E Checklist'));
		$columns = array();
		$options = array();
		foreach ($checklist->sections as $section) 
		{
			if($section->isScorable())
				array_push($columns, $section);
		}
		foreach ($columns as $column) {
			foreach ($column->questions as $question) 
			{
				if($question->answers->count()>0)
				{
					foreach ($question->answers as $answer) 
					{
						array_push($options, $answer->name);
					}
				}
			}
		}
		$options = array_unique($options);
		//	Chart title
		$title = '';
		//	Get counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		$to = Input::get('to');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		}
		elseif(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
		}
		$n = 0;
		//	Update chart title
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL)
			{
				if($site!=NULL)
				{
					
						$n = $checklist->surveys()->where('facility_id', $site);
						if($from && $to)
						{
							$n = $n->whereBetween('date_submitted', [$from, $toPlusOne]);
						}
						$n = $n->count();
					$title = Facility::find($site)->name.'(N='.$n.')';
				}
				else
				{					
					$n = $checklist->surveys()->join('facilities', 'surveys.facility_id', '=', 'facilities.id')
								->where('sub_county_id', $sub_county);
								if($from && $to)
								{
									$n = $n->whereBetween('date_submitted', [$from, $toPlusOne]);
								}
								$n = $n->count();				
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1).'(N='.$n.')';
				}
			}
			else
			{
				$n = $checklist->surveys()->join('facilities', 'surveys.facility_id', '=', 'facilities.id')
							->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
							->where('county_id', $jimbo);
							if($from && $to)
							{
								$n = $n->whereBetween('date_submitted', [$from, $toPlusOne]);
							}
							$n = $n->count();
				$title = County::find($jimbo)->name.' '.Lang::choice('messages.county', 1).'(N='.$n.')';
			}
		}
		else
		{
			$n = $checklist->surveys;
			if($from && $to)
			{
				$n = $n->whereBetween('date_submitted', [$from, $toPlusOne]);
			}
			$n = $n->count();
			$title = 'Kenya'.'(N='.$n.')';
		}
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: '".Lang::choice('messages.current-implementing-stage-chart', 1).$title."'
	        },
		    subtitle: {
		        text:"; 
		        if($from==$to)
		        	$chart.="'".trans('messages.for-the-year').' '.date('Y')."'";
		        else
		        	$chart.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
		    $chart.="},
	        xAxis: {
	            categories: [";
	            	foreach ($columns as $column) {
	            		$chart.="'".$column->label."',";
	            	}
	            $chart.="]
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: '% Score'
	            }
	        },
	        credits: {
			    enabled: false
			},
	        tooltip: {
	            pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b>%<br/>'
	        },
	        plotOptions: {
	            column: {
	                colorByPoint: true
	            }
	        },
	        series: [";
	        	$counts = count($options);
		        foreach ($options as $option) {
		        	$chart.="{colorByPoint: false,name:"."'".$option."'".", data:[";
	        		$counter = count($columns);
	        		foreach ($columns as $column) {
	        			$data = $column->column($jimbo, $sub_county, $site, $from, $toPlusOne)!=0?round(Answer::find(Answer::idByName($option))->column($column->id, $jimbo, $sub_county, $site, $from, $toPlusOne)*100/$column->column($jimbo, $sub_county, $site, $from, $toPlusOne), 2):0.00;
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
            		$chart.="], color:"."'".$colors[$counts-1]."'";
	            	if($counts==1)
						$chart.="}";
					else
						$chart.="},";
					$counts--;
		        }
		        $chart.="],
	    }";
		return view('report.me.stage', compact('checklist', 'columns', 'options', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'title', 'from', 'to', 'toPlusOne'));
	}

	/**
	 * Return snapshot of average score for each pillar at the given level
	 *
	 * @return Response
	 */
	public function snapshot()
	{	//	Get counties
		//	Get counties
		$counties = County::lists('name', 'id');
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		$to = Input::get('to');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		}
		elseif(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
		}
		
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('M & E Checklist'));
		$columns = array();
		$options = array();
		foreach ($checklist->sections as $section) 
		{
			if($section->isScorable())
				array_push($columns, $section);
		}
		foreach ($columns as $column) {
			foreach ($column->questions as $question) 
			{
				if($question->answers->count()>0)
				{
					foreach ($question->answers as $answer) 
					{
						array_push($options, $answer->name);
					}
				}
			}
		}
		$options = array_unique($options);
		//	Colors to be used in the series
		$colors = array();
		$chart = "{
			chart: {
				type: 'column'
			},
	        xAxis: {
	            categories: [";
	            	foreach ($columns as $column) {
	            		$chart.="'".$column->label."',";
	            	}
	            $chart.="],
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: '% Score'
	            }
	        },
	        credits: {
			    enabled: false
			},
			plotOptions: {
				column: {
					colorByPoint: true
				}
			},
			series: [{name: 'Snapshot',
				data: [";
				$counter = count($columns);
				$color = NULL;
				foreach ($columns as $column) {
					$value = $column->snapshot($jimbo, $sub_county, $site, $from, $toPlusOne);
					if($value >= 0 && $value <25)
						$color = '#d9534f';
					else if($value >=25 && $value <50)
						$color = '#f0ad4e';
					else if($value >=50 && $value <75)
						$color = '#d6e9c6';
					else if($value >=75 && $value <=100)
						$color = '#5cb85c';
					array_push($colors, $color);
					$chart.= $value;
					if($counter==1)
    					$chart.="";
    				else
    					$chart.=",";
    				$counter--;
				}
				$chart.="]
			}],
			colors:["."'".implode("','", $colors)."'"."]          
		}";
		return view('report.me.snapshot', compact('checklist', 'columns', 'options', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'from', 'to', 'toPlusOne'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function periodic($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		$columns = array(Lang::choice('messages.sites-using-htc', 1), Lang::choice('messages.sites-stock-out', 1), Lang::choice('messages.consistent-agreement-rate', 1), Lang::choice('messages.htc-data-reviewed', 1), Lang::choice('messages.sites-received-feedback', 1));
		return view('report.partner.period', compact('checklist', 'columns'));
	}

	/**
	 * Show accomplishment
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function accomplishment()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('M & E Checklist'));
		$columns = array();
		foreach ($checklist->sections as $section) 
		{
			if($section->isScorable())
				array_push($columns, $section);
		}
		//	Quarters array - Baseline, Previous Quarter, Quarter 1,2,3,4
		$options = array('Baseline', 'Previous Quarter', 'Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4');
		$date = Carbon::now();
        $date->month($date->month-6);
        $lastQuarter = $date->quarter;
        $fQuarter = $date->quarter;
		return view('report.accomplishment.index', compact('checklist', 'columns', 'options', 'lastQuarter', 'fQuarter'));
	}

	/**
	 * Return hr report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function hr()
	{
		//	Get checklist
		$checklist = Checklist::find(2);
		return view('report.hr.index', compact('checklist'));
	}
	/**
	 * Return pt report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function pt()
	{
		//	Get checklist
		$checklist = Checklist::find(2);
		//	Columns array
		$columns = array(Lang::choice('messages.sites-enrolled', 1), Lang::choice('messages.pt-results', 1), Lang::choice('messages.satisfactory-results', 1), Lang::choice('messages.corrective-feedback', 1));
		//	Periods
		$periods = array('Baseline', 'Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4');
		return view('report.pt.period', compact('checklist', 'columns', 'periods'));
	}
	/**
	 * Return pt-sdp report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function ptSdp()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get sdps
		$sdps = array_unique($checklist->surveys->lists('sdp_id'));
		//	Periods
		$periods = array('Baseline', 'Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4');
		return view('report.pt.sdp', compact('checklist', 'sdps', 'periods'));
	}
	/**
	 * Return logbook report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function logbook()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get columns
		$columns = array(Lang::choice('messages.sites-using-htc', 1), Lang::choice('messages.sites-stock-out', 1), Lang::choice('messages.consistent-agreement-rate', 1), Lang::choice('messages.htc-data-reviewed', 1), Lang::choice('messages.sites-received-feedback', 1));
		//	Periods
		$periods = array('Baseline', 'Previous Quarter', 'Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4');
		return view('report.logbook.period', compact('checklist', 'columns', 'periods'));
	}
	/**
	 * Return logbook - sdp report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function logSdp()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get sdps
		$sdps = array_unique($checklist->surveys->lists('sdp_id'));
		//	Agreement rates
		$rates = array('< 95%', '95 - 98%', '> 98%');
		return view('report.logbook.sdp', compact('checklist', 'sdps', 'rates'));
	}
	/**
	 * Return logbook - region report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function logRegion()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get regions
		$regions = array();
		$subs = array();
		$facility = $checklist->surveys->lists('facility_id');
		foreach ($facility as $id) {
			array_push($subs, Facility::find($id)->subCounty->id);
		}
		$subs = array_unique($subs);
		foreach ($subs as $sub) {
			array_push($regions, SubCounty::find($sub)->county->id);
		}
		$regions = array_unique($regions);
		//	Agreement rates
		$rates = array('< 95%', '95 - 98%', '> 98%');
		return view('report.logbook.region', compact('checklist', 'regions', 'rates'));
	}
	/**
	 * Return sprt report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function sprt()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		$columns = array();
		$periods = array('Baseline', 'Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4');
		foreach ($checklist->sections as $section) 
		{
			if($section->isScorable())
				array_push($columns, $section);
		}
		$colors = array('#434348', '#f45b5b', '#7cb5ec', '#2b908f', '#e4d354');
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
	            	foreach ($columns as $column) {
	            		$chart.="'".$column->label."',";
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
	        credits: {
			    enabled: false
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
	        series: [";
	        	$counts = count($periods);
		        foreach ($periods as $period) {
		        	$chart.="{colorByPoint: false,name:"."'".$period."'".", data:[";
	        		$counter = count($columns);
	        		foreach ($columns as $column) {
	        			$data = $column->quarter($period);
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
            		$chart.="], color:"."'".$colors[$counts-1]."'";
	            	if($counts==1)
						$chart.="}";
					else
						$chart.="},";
					$counts--;
		        }
		        $chart.="],
	    }";
		return view('report.spirt.section', compact('checklist', 'columns', 'periods', 'chart'));
	}
	/**
	 * Return partner period report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function period()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get levels
		$levels = Level::all();
		//	Get periods
		$periods = array('Baseline', 'Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4');
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1)."'
	        },
	        subtitle: {
	            text: 'Source: HIV-QA Kenya'
	        },
	        xAxis: {
	            categories: [";
	            	foreach ($periods as $period) {
	            		$chart.="'".$period."',";
	            	}
	            $chart.="]
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Percentage'
	            }
	        },
	        credits: {
			    enabled: false
			},
	        tooltip: {
	            headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
	            pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
	                '<td style=\"padding:0\"><b>{point.y:.1f} %</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0,
	                colorByPoint: true
	            }
	        },
	        colors: ['red', 'orange', 'yellow', '#90ED7D', 'green'],
	        series: [";
	        	$counts = count($levels);
		        foreach ($levels as $level) {
		        	$chart.="{colorByPoint: false,name:"."'".$level->name.' ('.$level->range_lower.'-'.$level->range_upper.'%)'."'".", data:[";
	        		$counter = count($periods);
	        		foreach ($periods as $period) {
	        			$data = $checklist->level($level);
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
		return view('report.spirt.period', compact('checklist', 'levels', 'periods', 'chart'));
	}	
	/**
	 * Return partner region report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function region()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get levels
		$levels = Level::all();
		//	Get regions
		$regions = array();
		$subs = array();
		$facility = $checklist->surveys->lists('facility_id');
		foreach ($facility as $id) {
			array_push($subs, Facility::find($id)->subCounty->id);
		}
		$subs = array_unique($subs);
		foreach ($subs as $sub) {
			array_push($regions, SubCounty::find($sub)->county->id);
		}
		$regions = array_unique($regions);
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1)."'
	        },
	        subtitle: {
	            text: 'Source: HIV-QA Kenya'
	        },
	        xAxis: {
	            categories: [";
	            	foreach ($regions as $region) {
	            		$chart.="'".County::find($region)->name."',";
	            	}
	            $chart.="]
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Percentage'
	            }
	        },
	        credits: {
			    enabled: false
			},
	        tooltip: {
	            headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
	            pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
	                '<td style=\"padding:0\"><b>{point.y:.1f} %</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0,
	                colorByPoint: true
	            }
	        },
	        colors: ['red', 'orange', 'yellow', '#90ED7D', 'green'],
	        series: [";
	        	$counts = count($levels);
		        foreach ($levels as $level) {
		        	$chart.="{colorByPoint: false,name:"."'".$level->name.' ('.$level->range_lower.'-'.$level->range_upper.'%)'."'".", data:[";
	        		$counter = count($regions);
	        		foreach ($regions as $region) {
	        			$data = $checklist->level();
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
		return view('report.spirt.region', compact('checklist', 'levels', 'regions', 'chart'));
	}
	/**
	 * Return partner sdp report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function sdp()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get levels
		$levels = Level::all();
		//	Get sdps
		$sdps = array_unique($checklist->surveys->lists('sdp_id'));
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1)."'
	        },
	        subtitle: {
	            text: 'Source: HIV-QA Kenya'
	        },
	        xAxis: {
	            categories: [";
	            	foreach ($sdps as $sdp) {
	            		$chart.="'".Sdp::find($sdp)->name."',";
	            	}
	            $chart.="]
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Percentage'
	            }
	        },
	        credits: {
			    enabled: false
			},
	        tooltip: {
	            headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
	            pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
	                '<td style=\"padding:0\"><b>{point.y:.1f} %</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0,
	                colorByPoint: true
	            }
	        },
	        colors: ['red', 'orange', 'yellow', '#90ED7D', 'green'],
	        series: [";
	        	$counts = count($levels);
		        foreach ($levels as $level) {
		        	$chart.="{colorByPoint: false,name:"."'".$level->name.' ('.$level->range_lower.'-'.$level->range_upper.'%)'."'".", data:[";
	        		$counter = count($sdps);
	        		foreach ($sdps as $sdp) {
	        			$data = $checklist->level();
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
		return view('report.spirt.sdp', compact('checklist', 'levels', 'sdps', 'chart'));
	}
	/**
	 * Return eval report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function evaluation()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('M & E Checklist'));
		$columns = array();
		$options = array();
		foreach ($checklist->sections as $section) 
		{
			if($section->isScorable())
				array_push($columns, $section);
		}
		foreach ($columns as $column) {
			foreach ($column->questions as $question) 
			{
				if($question->answers->count()>0)
				{
					foreach ($question->answers as $answer) 
					{
						array_push($options, $answer->name);
					}
				}
			}
		}
		$options = array_unique($options);
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'Current Assessment Information'
	        },
	        xAxis: {
	            categories: [";
	            	foreach ($columns as $column) {
	            		$chart.="'".$column->label."',";
	            	}
	            $chart.="]
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: '% Score'
	            }
	        },
	        credits: {
			    enabled: false
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
		        	$chart.="{colorByPoint: false,name:"."'".$option."'".", data:[";
	        		$counter = count($columns);
	        		foreach ($columns as $column) {
	        			$data = Answer::find(Answer::idByName($option))->column($column->id);
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
            		$chart.="], color:"."'".$colors[$counts-1]."'";
	            	if($counts==1)
						$chart.="}";
					else
						$chart.="},";
					$counts--;
		        }
		        $chart.="],
	    }";
		return view('report.me.section', compact('checklist', 'columns', 'options', 'chart'));
	}
	/**
	 * Return data report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function data()
	{
		//	Get checklist
		$checklist = Checklist::find(2);
		return view('report.me.analysis', compact('checklist'));
	}
	/**
	* Get months: return months for time_created column when filter dates are set
	*/	
	public static function getMonths($from, $to)
	{
		$checklist = Checklist::find(Checklist::idByName('HTC Lab Register (MOH 362)'));
		$today = "'".date("Y-m-d")."'";
		$year = date('Y');
		$surveys = $checklist->surveys()->select('date_submitted')->distinct();

		if(strtotime($from)===strtotime($today)){
			$surveys = $surveys->where('date_submitted', 'LIKE', $year.'%');
		}
		else
		{
			$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
			$surveys = $surveys->whereBetween('date_submitted', array($from, $toPlusOne));
		}
		$allDates = array();
		$allSurveyDates = $surveys->lists('date_submitted');
		foreach ($allSurveyDates as $surveyDate)
		{
			array_push($allDates, Carbon::createFromFormat('Y-m-d H:i:s', $surveyDate)->toDateString());
		}
		$allDates = array_unique($allDates);
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
	/**
	* Get quarter: return the specific quarter given the month
	*/
	public static function getQuarter($month)
	{
		$n = $month;
		if($n < 4)
			return '2';
		else if($n > 3 && $n < 7)
			return '3';
		else if($n > 6 && $n < 10)
			return '4';
		else if($n > 9)
			return '1';
    }
}
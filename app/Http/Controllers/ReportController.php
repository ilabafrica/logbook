<?php namespace App\Http\Controllers;
set_time_limit(0);
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
use App\Models\SurveySdp;
use App\Models\FacilitySdp;

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
		
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		//	Declare variables
		$sdps = [];
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		// $facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Get sdps
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$months = json_decode(self::getMonths($from, $to, $checklist->id));
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$fsdps = $variables['sdps'];
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-positive', 1).'-'.$title."'
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
            	foreach ($months as $month)
            	{
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
	        tooltip: {
	            valueSuffix: '%'
	        },
	        credits: {
			    enabled: false
			},
	        series: [";
	        $counts = count($fsdps);
	        foreach ($fsdps as $fsdp)
	        {
	        	$name = '';
        		if($site || $sdp)
        			$name = FacilitySdp::cojoin($fsdp);
        		else
        			$name = Sdp::find($fsdp)->name;
	        	$chart.="{name:"."'".$name."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			if($site || $sdp)
        				$data =  FacilitySdp::find($fsdp)->positivePercent($sdp, $site, $sub_county, $jimbo, $month->annum, $month->months);
        			else
        				$data = Sdp::find($fsdp)->positivePercent(NULL, $sub_county, $jimbo, $month->annum, $month->months);
        			if($data==0)
        			{
    					$chart.= '0.00';
    					if($counter==1)
        					$chart.="";
        				else
        					$chart.=",";
    				}
    				else
    				{
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
		return view('report.htc.positive', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site','sdps', 'sdp'));
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
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
		//	Declare variables
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$kit = NULL;
		$kit = Input::get('kit');
		if($kit==NULL)
		{
			$kit = 'KHB';
		}
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$jimbo = County::find(Auth::user()->tier->tier);
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$sub_county = SubCounty::find(Auth::user()->tier->tier);
		//	Get sdps
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$fsdps = $variables['sdps'];
		$months = json_decode(self::getMonths($from, $to, $checklist->id));
		$posAgr = [];
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-positiveAgr', 1).'-'.$title."'
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
            	foreach ($months as $month)
            	{
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
	        tooltip: {
	            valueSuffix: '%'
	        },
	        credits: {
			    enabled: false
			},
	        series: [";
	        $counts = count($fsdps);
	        foreach ($fsdps as $fsdp)
	        {
	        	$name = '';
        		if($site || $sdp)
        			$name = FacilitySdp::cojoin($fsdp);
        		else
        			$name = Sdp::find($fsdp)->name;
	        	$chart.="{name:"."'".$name."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			if($site || $sdp)
        				$data = $fsdp->positiveAgreement($kit, $site, $sub_county, $jimbo, $month->annum, $month->months);
        			else
        				$data = Sdp::find($fsdp)->positiveAgreement($kit, $month->annum, $month->months);
        			$posAgr[] = array('year' => $month->annum, 'month' => $month->months, 'fsdp' => $fsdp, 'agreement' => $data);
        			if($data==0)
        			{
    					$chart.= '0.00';
    					if($counter==1)
        					$chart.="";
        				else
        					$chart.=",";
    				}
    				else
    				{
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
	    //	Percent of sites
	    $percentages = array('<95%', '95-98%', '>98%');
	    $percent = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1).'-'.$title."'
	        },
		    subtitle: {
		        text:"; 
		        if($from==$to)
		        	$percent.="'".trans('messages.for-the-year').' '.date('Y')."'";
		        else
		        	$percent.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
		    $percent.="},
		    xAxis:{
		    	type: 'category'
		    },
	        yAxis: {
	            title: {
	                text: '".Lang::choice('messages.percent-of-sites', 1)."'
	            }
	        },
	        credits: {
			    enabled: false
			},
			colors: ['red', 'yellow', 'green'],
			plotOptions: {
	            dataLabels:{
	            	enabled:true
	            }
	        },
	        tooltip: {
	            valueSuffix: '%'
	        },
	        series: [";
	        $counts = count($percentages);
	        foreach ($percentages as $percentage)
	        {
	        	$percent.="{name:"."'".$percentage."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			$percent.="{name:"."'".$month->label.' '.$month->annum."'".", y:";
        			$data = $checklist->positiveAgreement($percentage, $posAgr, NULL, NULL, $month->annum, $month->months);
        			if($data==0)
        			{
    					$percent.= '0.00'.", drilldown:"."'".$percentage.'_'.$month->months.'_'.$month->annum."'"."}";
    					if($counter==1)
        					$percent.="";
        				else
        					$percent.=",";
    				}
    				else
    				{
        				$percent.= $data.", drilldown:"."'".$percentage.'_'.$month->months.'_'.$month->annum."'"."}";

        				if($counter==1)
        					$percent.="";
        				else
        					$percent.=",";
    				}
        			$counter--;
        		}
        		$percent.="]";
            	if($counts==1)
					$percent.="}";
				else
					$percent.="},";
				$counts--;
	        }
	        $percent.="]
	    }";
		return view('report.htc.agreement', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site', 'sdps','sdp', 'percent', 'kit'));
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
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
		//	Declare variables
		$sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$kit = NULL;
		$kit = Input::get('kit');
		if($kit==NULL)
		{
			$kit = 'KHB';
		}
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}	
		//	Get sdps
		$sdps = array();
		//	Percentages
		$percentages = array('<95%', '95-98%', '>98%');
		//	Get sdps
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$fsdps = $variables['sdps'];
		$months = json_decode(self::getMonths($from, $to, $checklist->id));
		$overAgr = [];
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-overallAgr', 1).'-'.$title."'
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
            	foreach ($months as $month)
            	{
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
	        tooltip: {
	            valueSuffix: '%'
	        },
	        series: [";
	        $counts = count($fsdps);
	        foreach ($fsdps as $fsdp)
	        {
	        	$name = '';
        		if($site || $sdp)
        			$name = FacilitySdp::cojoin($fsdp);
        		else
        			$name = Sdp::find($fsdp)->name;
	        	$chart.="{name:"."'".$name."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			if($site || $sdp)
        				$data = $fsdp->overallAgreement($kit, $site, $sub_county, $jimbo, $month->annum, $month->months);
        			else
        				$data = Sdp::find($fsdp)->overallAgreement($kit, $month->annum, $month->months);
        			$overAgr[] = array('year' => $month->annum, 'month' => $month->months, 'fsdp' => $fsdp, 'agreement' => $data);
        			if($data==0)
        			{
    					$chart.= '0.00';
    					if($counter==1)
        					$chart.="";
        				else
        					$chart.=",";
    				}
    				else
    				{
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
	    //	Percent of sites
	    $percent = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1).'-'.$title."'
	        },
		    subtitle: {
		        text:"; 
		        if($from==$to)
		        	$percent.="'".trans('messages.for-the-year').' '.date('Y')."'";
		        else
		        	$percent.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
		    $percent.="},
		    xAxis:{
		    	type: 'category'
		    },
	        yAxis: {
	            title: {
	                text: '".Lang::choice('messages.percent-of-sites', 1)."'
	            }
	        },
	        credits: {
			    enabled: false
			},
			colors: ['red', 'yellow', 'green'],
			plotOptions: {
	            dataLabels:{
	            	enabled:true
	            }
	        },
	        tooltip: {
	            valueSuffix: '%'
	        },
	        series: [";
	        $counts = count($percentages);
	        foreach ($percentages as $percentage)
	        {
	        	$percent.="{name:"."'".$percentage."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			$percent.="{name:"."'".$month->label.' '.$month->annum."'".", y:";
        			$data = $checklist->overallAgreement($percentage, $overAgr, $month->annum, $month->months);
        			if($data==0)
        			{
    					$percent.= '0.00'.", drilldown:"."'".$percentage.'_'.$month->months.'_'.$month->annum."'"."}";
    					if($counter==1)
        					$percent.="";
        				else
        					$percent.=",";
    				}
    				else
    				{
        				$percent.= $data.", drilldown:"."'".$percentage.'_'.$month->months.'_'.$month->annum."'"."}";

        				if($counter==1)
        					$percent.="";
        				else
        					$percent.=",";
    				}
        			$counter--;
        		}
        		$percent.="]";
            	if($counts==1)
					$percent.="}";
				else
					$percent.="},";
				$counts--;
	        }
	        $percent.="]
	    }";
		return view('report.htc.overall', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site', 'percent', 'sdps', 'sdp', 'kit'));

	}
	/**
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
		//dd(Input::all());
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		
		//	Declare variables
		$sdps = array();
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Update chart title
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$categories = array();
		$options = array();
		foreach ($checklist->sections as $section) 
		{
			if($section->isScorable())
				array_push($categories, $section);
		}
		foreach ($categories as $category)
		{
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
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {

	            text: '".Lang::choice('messages.summary-chart', 1).'-'.$title."'

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
		        foreach ($options as $option)
		        {
		        	$response = Answer::find(Answer::idByName($option));
		        	$chart.="{colorByPoint: false,name:"."'".$response->name." (".$response->range_lower."-".$response->range_upper."%)'".", data:[";
	        		$counter = count($categories);
	        		foreach ($categories as $category)
	        		{
	        			$data = $category->level($id, $option, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
	        			if($data==0)
	        			{
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

	    //dd($subCounties);
		return view('report.me.mscolumn', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo','sdps','sdp', 'sub_county', 'site'));

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
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$months = json_decode(self::getMonths($from, $to));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Update chart title
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
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
	            	foreach ($categories as $category)
	            	{
	   					$chart.=$category->spider($sdp, $site, $sub_county, $jimbo, $from, $toPlusOne).',';
	   				}
	   				$chart.="],
	            pointPlacement: 'on'
	        }]

	    }";
	    //	Get data for table
	    $data = array();
	    foreach ($categories as $category)
	    {
	    	$data[$category->id] = $category->spider($sdp, $site, $sub_county, $jimbo, $from, $toPlusOne);
	    }

	    $score = $checklist->level(NULL, $jimbo, $sub_county, $site, $from, $toPlusOne);
	    $level = $checklist->levelCheck($score);
	    return view('report.spirt.spider', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'categories', 'data', 'title', 'from', 'to', 'jimbo', 'sub_county', 'site','sdps', 'sdp', 'score', 'level'));
	}
	/**
	 * Show the table for current stage of sites implementing RTQII priority activities in Country X (percentage of sites)..
	 *
	 * @return Response
	 */
	public function chart()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('M & E Checklist'));
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = SubCounty::lists('name', 'id');
		$jimbo = NULL;
		$sub_county = NULL;
		if(Input::get('county') && !(Input::get('sub_county')))
			$jimbo = Input::get('county');
		else if(Input::get('county') && !(Input::get('sub_county')))
			$sub_county = Input::get('sub_county');
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
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		//$sdps = Sdp::lists('name', 'id');
		$sdps =array();
		//	Declare variables
		//dd($sdps);
		$sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$today = "'".date("Y-m-d")."'";
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($checklist->id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		$n = 0;
		//	Update chart title
		$variables = $this->sdpsTitleN($checklist->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
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
	        			$data = $column->column($option, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
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
		return view('report.me.stage', compact('checklist', 'columns', 'options', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site','sdps','sdp', 'title', 'from', 'to', 'toPlusOne'));
	}

	/**
	 * Return snapshot of average score for each pillar at the given level
	 *
	 * @return Response
	 */
	public function snapshot()
	{	
		$id = Checklist::idByName('M & E Checklist');
		$checklist = Checklist::find($id);
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
		$sdp = NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		
		//	Update chart title
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
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
	        title: {
	            text: '".Lang::choice('messages.snapshot-label', 1).$title."'
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
	            $chart.="],
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: '% Score'
	            }
	        },
	        tooltip: {
	            valueSuffix: '%'
	        },
	        credits: {
			    enabled: false
			},
	        legend: {
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
					$value = $column->snapshot($jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
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
		return view('report.me.snapshot', compact('checklist', 'columns', 'options', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site','sdps', 'sdp', 'from', 'to', 'toPlusOne', 'title'));
	}

	public function breakdown()
	{	
		$id = Checklist::idByName('M & E Checklist');
		$checklist = Checklist::find($id);
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
		$sdp = NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		
		//	Update chart title
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('M & E Checklist'));
		$domain = array();
		$options = array();
		$columns = $checklist->sections;
		foreach ($columns as $column)
		{
			$domain = array_merge($domain, $column->questions()->where('score', '!=', '0')->lists('id'));
			foreach ($column->questions as $question) 
			{
				if($question->answers->count()>0)
				{
					foreach ($question->answers as $answer) 
					{
						array_push($options, $answer->name);
					}
				}/*
				if($question->score>0)
					array_push($domain, $question->id)*/
			}
		}
		$options = array_unique($options);
		//	Colors to be used in the series
		return view('report.me.breakdown', compact('checklist', 'columns', 'options', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site','sdps', 'sdp', 'from', 'to', 'toPlusOne', 'title','domain'));
	}

	/**
	 * M$E stacked percentages report
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function response()
	{
		//dd(Input::all());
		//	Get checklist
		$checklist_id = Checklist::idByName('M & E Checklist');
		$checklist = Checklist::find($checklist_id);
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		
		//	Declare variables
		$sdps = array();
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($checklist_id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Update chart title
		$variables = $this->sdpsTitleN($checklist_id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
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
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {

	            text: '".Lang::choice('messages.summary-chart', 1).'-'.$title."'

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
            	foreach ($categories as $category)
            	{
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
		        foreach ($options as $option)
		        {
		        	$chart.="{colorByPoint: false, name:"."'".Answer::find(Answer::idByName($option))->name."'".", data:[";
	        		$counter = count($categories);
	        		foreach ($categories as $category)
	        		{
	        			$data = Answer::find(Answer::idByName($option))->column($category->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
	        			if($data==0)
	        			{
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
		return view('report.me.response', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo','sdps','sdp', 'sub_county', 'site'));
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
	            text: 'SPI-RT Scores Comparison for $title',
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
	            text: '".Lang::choice('messages.percent-of-sites', 1).'-'.$title."'
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
	            shared: true,
	            pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y} %</b><br/>',
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
	/*public function region()
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
	}*/
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
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
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
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$id = Checklist::idByName('SPI-RT Checklist');
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
			$sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Update chart title
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, NULL, $from, $toPlusOne);
		$title = $variables['title'];
		$fsdps = $variables['sdps'];
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1).'-'.$title."'
	        },
	        subtitle: {
	            text: 'Source: HIV-QA Kenya'
	        },
	        xAxis: {
	            categories: [";
            	foreach ($fsdps as $fsdp)
            	{
            		$name = '';
            		if($site)
            			$name = FacilitySdp::cojoin($fsdp);
            		else
            			$name = Sdp::find($fsdp)->name;
            		$chart.="'".$name."',";
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
	            shared: false,
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
		        foreach ($levels as $level)
		        {
		        	$chart.="{colorByPoint: false,name:"."'".$level->name.' ('.$level->range_lower.'-'.$level->range_upper.'%)'."'".", data:[";
	        		$counter = count($fsdps);
	        		foreach ($fsdps as $fsdp)
	        		{
	        			if($site)
	        				$data = FacilitySdp::find($fsdp)->level($level->id, $from, $toPlusOne);
	        			else
	        				$data = Sdp::find($fsdp)->level($level->id, $jimbo, $sub_county, NULL, NULL, $from, $toPlusOne);
	        			if($data==0)
	        			{
        					$chart.= '0.00';
        					if($counter==1)
            					$chart.="";
            				else
            					$chart.=",";
        				}
        				else
        				{
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
		return view('report.spirt.level', compact('checklist', 'levels', 'sdps', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'title', 'from', 'to', 'fsdps'));
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
	public static function getMonths($from, $to, $htc  = null)
	{
		$today = "'".date("Y-m-d")."'";
		$year = date('Y');
		if($htc)
			$surveys = Survey::select('data_month')->distinct();
		else
			$surveys = Survey::select('date_submitted')->distinct();

		if(strtotime($from)===strtotime($today)){
			if($htc)
				$surveys = $surveys->where('data_month', 'LIKE', $year.'%');
			else
				$surveys = $surveys->where('date_submitted', 'LIKE', $year.'%');
		}
		else
		{
			$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
			if($htc)
				$surveys = $surveys->whereBetween('data_month', array($from, $to));
			else
				$surveys = $surveys->whereBetween('date_submitted', array($from, $toPlusOne));
		}
		$allDates = array();
		if($htc)
			$allSurveyDates = $surveys->lists('data_month');
		else
			$allSurveyDates = $surveys->lists('date_submitted');
		foreach ($allSurveyDates as $surveyDate)
		{
			array_push($allDates, Carbon::parse($surveyDate)->toDateString());
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
	/**
	 * Show the application landing page upon successful signin.
	 *
	 * @return Response
	 */
	public function dashboard()
	{
		//	Get complete sites
		$counter = 0;
		$htc = Checklist::find(Checklist::idByName('HTC Lab Register (MOH 362)'));
		$me = Checklist::find(Checklist::idByName('M & E Checklist'));
		$spi = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		$checklists = [$htc, $me, $spi];
		//	Get counties
		$counties = $me->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$sites = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$sites = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
		//	Declare variables
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		//$facility = Facility::find(2);
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$months = json_decode(self::getMonths($from, $to));
	    //	Pie chart for county submissions
	    $pie = [];
	    foreach($checklists as $checklist)
	    {
		    $pie[$checklist->id] = "{
		        chart: {
		            type: 'pie'
		        },
		        title: {
		            text: '".$checklist->name."'
		        },
		        xAxis: {
		            type: 'category'
		        },

		        legend: {
		            enabled: false
		        },
		        credits: {
		            enabled: false
		        },
		        plotOptions: {
		            series: {
		                borderWidth: 0,
		                dataLabels: {
		                    enabled: true,
		                }
		            }
		        },

		        series: [{
		            name: 'Total',
		            colorByPoint: true,
		            data: [";
		            	foreach ($checklist->countiesWithData() as $key => $value)
		            	{
		            		$county = County::find($key);
		            		$pie[$checklist->id].="{
				                name: '".$value."',
				                y: ".$checklist->fsdps($checklist->id, $county->id, NULL, NULL, NULL, $from, $toPlusOne)->count().",
				                drilldown: '".$county->id."'
				            },";
		            	}
		            	$pie[$checklist->id].="
		            ]
		        }],
		        drilldown: {
		            series: [";
		            foreach ($checklist->countiesWithData() as $key => $value)
	            	{
	            		$county = County::find($key);
	            		$pie[$checklist->id].="{
			                id: '".$key."',
			                name: 'Total',
			                data: [";
			                foreach ($county->subCounties as $subCounty)
			                {
			                	$pie[$checklist->id].="{
				                    name: '".$subCounty->name."',
				                    y: ".$checklist->fsdps($checklist->id, NULL, $subCounty->id, NULL, NULL, $from, $toPlusOne)->count().",
				                    drilldown: '".$subCounty->name."'
				                },";
			                }
			                $pie[$checklist->id].="]
			            },";
	            	}
	            	foreach ($checklist->countiesWithData() as $key => $value)
	            	{
	            		$county = County::find($key);
		                foreach ($county->subCounties as $subCounty)
		                {
		                	$pie[$checklist->id].="{
				                id: '".$subCounty->name."',
				                name: 'Total',
				                data: [";
			                	foreach ($subCounty->facilities as $facility)
			                	{
			                		$pie[$checklist->id].="{
					                    name: '".$facility->name."',
					                    y: ".$checklist->fsdps($checklist->id, NULL, NULL, $facility->id, NULL, $from, $toPlusOne)->count().",
					                    drilldown: '".$facility->id.'_'.$subCounty->id."'
					                },";
					            }
					            $pie[$checklist->id].="]
				            },";
		                }
	            	}
	            	foreach ($checklist->countiesWithData() as $key => $value)
	            	{
	            		$county = County::find($key);
		                foreach ($county->subCounties as $subCounty)
		                {
		                	foreach ($subCounty->facilities as $facility)
			                {
			                	$pie[$checklist->id].="{
					                id: '".$facility->id.'_'.$subCounty->id."',
					                data: [";
				                	foreach ($facility->facilitySdp as $fsdp)
				                	{
				                		$pie[$checklist->id].="{
						                    name: '".FacilitySdp::cojoin($fsdp->id)."',
						                    y: ".$checklist->fsdps($checklist->id, NULL, NULL, NULL, $fsdp->id, $from, $toPlusOne)->count().",
						                    drilldown: '".FacilitySdp::cojoin($fsdp->id)."'
						                },";
						            }
						            $pie[$checklist->id].="]
					            },";
				        	}
		                }
	            	}
	            	$pie[$checklist->id].="]
		        }
		    }";
		}
	    // msline for submissions for the 3 checklists
	    $msline = "{
	    	chart: {
	            type: 'column'
	        },
	        title: {
	            text: 'Monthly Data Submissions Per Checklist',
	            x: -20 //center
	        },
	        subtitle: {
	            text: 'Source: HIV-QA Kenya',
	            x: -20
	        },
	        xAxis: {
	        	categories: [";
	        	$count = count($months);
            	foreach ($months as $month)
            	{
    				$msline.= "'".$month->label.' '.$month->annum;
    				if($count==1)
    					$msline.="' ";
    				else
    					$msline.="' ,";
    				$count--;
    			}
	        $msline.="]
	    	},
	        yAxis: {
	            title: {
	                text: 'Submissions (N)'
	            },
	            plotLines: [{
	                value: 0,
	                width: 1,
	                color: '#808080'
	            }]
	        },
	        legend: {
	            enabled: true,
	            align: 'center',
	            verticalAlign: 'bottom',
	            y: 0,
	            padding: 0,
	            margin:5,
	            itemMarginTop: 0,
	            itemMarginBottom: 0,
	            itemStyle:{
	                fontSize: '10px'
	                }
	        },
	        credits: {
			    enabled: false
			},
	        series: [";
	        	$counts = count($checklists);
	        	foreach ($checklists as $checklist)
	        	{
	        		$counter = count($months);
	        		$msline.="{name:"."'".$checklist->name."'".", data:[";
		        	foreach ($months as $month)
		        	{
		        		$data = $checklist->fsdps($checklist->id, NULL, NULL, NULL, NULL, NULL, NULL, $month->annum, $month->months)->count();
			        	if($data==0){
        					$msline.= '0.00';
        					if($counter==1)
            					$msline.="";
            				else
            					$msline.=",";
	    				}
	    				else{
	        				$msline.= $data;
	        				if($counter==1)
	        					$msline.="";
	        				else
	        					$msline.=",";
	    				}
	        			$counter--;
	        		}
	        		$msline.="]";
	            	if($counts==1)
						$msline.="}";
					else
						$msline.="},";
					$counts--;
		        }
		    $msline.="
	        ]
	    }";
		return view('dashboard', compact('pie', 'from', 'to', 'msline', 'checklists'));
	}
	/**
	 * Display distribution of agreement rates among testing sites by programatic area
	 *
	 * @return Response
	 */
	public function programatic($id)
	{
		//	Retrieve HTC Lab Register
		$checklist = Checklist::find(Checklist::idByName('HTC Lab Register (MOH 362)'));
		
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
		//	Declare variables
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$kit = NULL;
		$kit = Input::get('kit');
		if($kit==NULL)
		{
			$kit = 'KHB';
		}
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Get sdps
		$sdps = array();
		//	Get sdps
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$variables = $this->sdpsTitleN($checklist->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$ssdps = $variables['sdps'];
		$months = json_decode(self::getMonths($from, $to));
		$percentages = array('<95%', '95-98%', '>98%');
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.programatic-area', 1).'-'.$title."'
	        },
		    subtitle: {
		        text:"; 
		        if($from==$to)
		        	$chart.="'".trans('messages.for-the-year').' '.date('Y')."'";
		        else
		        	$chart.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
		    $chart.="},
	        xAxis: {
	            type: 'category'
	        },
	        yAxis: {
	            title: {
	                text: '".Lang::choice('messages.percent-of-sites', 1)."'
	            },
	            min: 0,
	            max: 100
	        },
	        tooltip: {
	            valueSuffix: '%'
	        },
	        colors: ['red', 'yellow', 'green'],
	        credits: {
			    enabled: false
			},
	        series: [";
	        $counts = count($percentages);
	        foreach ($percentages as $percentage) {
	        	$chart.="{name:"."'".$percentage."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			$chart.="{name:"."'".$month->label.' - '.$month->annum."'".", y:";
        			$data = $checklist->positiveAgreement($percentage, $sdps, $kit, $site, $sub_county, $jimbo, $month->annum, $month->months, $from, $to);
        			if($data==0){
        					$chart.= '0.00'.", drilldown:"."'".$percentage.'_'.$month->months.'_'.$month->annum."'"."}";
        					if($counter==1)
            					$chart.="";
            				else
            					$chart.=",";
    				}
    				else{
        				$chart.= $data.", drilldown:"."'".$percentage.'_'.$month->months.'_'.$month->annum."'"."}";

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
		        drilldown: {
		            series: [";
		            foreach ($percentages as $percentage)
	        		{
	        			foreach ($months as $month)
        				{
        					$sticker = $percentage." - ".$month->label." ".$month->annum;
        					$combined = $percentage.'_'.$month->months.'_'.$month->annum;
        					$percent.="{name:"."'".$sticker."', "."id:"."'".$combined."'".", data:[";
        					foreach ($checklist->sdpPosAgreement($combined, $sdps, $kit, $site, $sub_county, $jimbo) as $sdp=>$per)
        					{
        						$percent.="["."'".$sdp."'".", ".$per."],";
        					}
        					$percent.="]},";
        				}
	        		}
	            $chart.="]
	        }
	    }";
		return view('report.htc.programatic', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site','sdps', 'sdp', 'kit'));
	}
	/**
	 * Display distribution of agreement rates among testing sites by geographic area
	 *
	 * @return Response
	 */
	public function geographic($id)
	{
		//	Retrieve HTC Lab Register
		$checklist = Checklist::find(Checklist::idByName('HTC Lab Register (MOH 362)'));
		
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties 
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
		//	Declare variables
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$kit = NULL;
		$kit = Input::get('kit');
		if($kit==NULL)
		{
			$kit = 'KHB';
		}
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Set title
		//	Get sdps
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$variables = $this->sdpsTitleN($checklist->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$fsdps = $variables['sdps'];
		$months = json_decode(self::getMonths($from, $to));
		$percentages = array('<95%', '95-98%', '>98%');
		$regions = $checklist->regions();
		$chart = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.geographic-location', 1).'-'.$title."'
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
            	foreach ($regions as $key => $value)
            	{
            		$chart.="'".$value."',";
            	}
	            $chart.="]
	        },
	        yAxis: {
	            title: {
	                text: '".Lang::choice('messages.percent-of-sites', 1)."'
	            },
	            min: 0,
	            max: 100
	        },
	        tooltip: {
	            valueSuffix: '%'
	        },
	        plotOptions: {
	        	series: {
		        	dataLabels: {
	                    enabled: true,
	                    formatter: function() {
	                        if (this.y != 0)
	                        	return this.y;
	                        else
	                        	return null;
	                    }
	                }
	            }
	        },
	        colors: ['red', 'yellow', 'green'],
	        credits: {
			    enabled: false
			},
	        series: [";
        	$counts = count($percentages);
	        foreach ($percentages as $percentage)
	        {
	        	$chart.="{colorByPoint: false,name:"."'".$percentage."'".", data:[";
	        	$counter = count($regions);
        		foreach ($regions as $key => $value)
        		{
        			$data = 0.00;
        			if($jimbo || $sub_county)
        			{
        				if($sub_county)
        					$data = $checklist->regionalAgreement($percentage, $kit, NULL, NULL, $key, $from, $toPlusOne);
        				else
        					$data = $checklist->regionalAgreement($percentage, $kit, NULL, $key, NULL, $from, $toPlusOne);
        			}
        			else
        			{
        				$data = $checklist->regionalAgreement($percentage, $kit, $key, NULL, NULL, $from, $toPlusOne);
        			}
        			if($data==0)
        			{
    					$chart.= '0.00';
    					if($counter==1)
        					$chart.="";
        				else
        					$chart.=",";
    				}
    				else
    				{
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
		return view('report.htc.geographic', compact('checklist', 'chart', 'counties', 'jimbo', 'subCounties', 'sub_county', 'facilities', 'from', 'to','sdps', 'sdp', 'kit'));
	}
	/**
	 * Return partner sdp report across regions
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function geoRegion()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get levels
		$levels = Level::all();
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdp = NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($checklist->id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Get sdps
		$variables = $this->sdpsTitleN($checklist->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$fsdps = $variables['sdps'];
		$regions = $checklist->regions($jimbo, $sub_county);
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1).' for - '.$title."'
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
            	foreach ($regions as $key => $value)
            	{
            		$chart.="'".$value."',";
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
	                colorByPoint: true,
		            series: {
		                stacking: 'normal'
		            }
	            }
	        },
	        colors: ['red', '#FF7F0E', 'yellow', '#90ED7D', 'green'],
	        series: [";
	        	$counts = count($levels);
		        foreach ($levels as $level)
		        {
		        	$chart.="{colorByPoint: false,name:"."'".$level->name.' ('.$level->range_lower.'-'.$level->range_upper.'%)'."'".", data:[";
		        	$counter = count($regions);
	        		foreach ($regions as $key => $value)
	        		{
	        			$data = 0.00;
	        			if($jimbo || $sub_county)
	        			{
	        				if($sub_county)
	        					$data = $checklist->level($level->id, NULL, NULL, $key, $from, $toPlusOne);
	        				else
	        					$data = $checklist->level($level->id, NULL, $key, NULL, $from, $toPlusOne);
	        			}
	        			else
	        			{
	        				$data = $checklist->level($level->id, $key, NULL, NULL, $from, $toPlusOne);
	        			}
	        			if($data==0)
	        			{
        					$chart.= '0.00';
        					if($counter==1)
            					$chart.="";
            				else
            					$chart.=",";
        				}
        				else
        				{
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
		return view('report.spirt.region', compact('checklist', 'levels', 'sdps', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'title', 'from', 'to'));
	}
	/**
	 * Return precertification levels overtime
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function precertOvertime()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get levels
		$levels = Level::all();
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdp = NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$months = json_decode(self::getMonths($from, $to));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($checklist->id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Get sdps
		$variables = $this->sdpsTitleN($checklist->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1).' for - '.$title."'
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
            	foreach ($months as $month)
            	{
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
	            min: 0,
	            title: {
	                text: 'Percentage'
	            },
	            min: 0,
	            max: 100
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
	        colors: ['red', '#FF7F0E', 'yellow', '#90ED7D', 'green'],
	        series: [";
        	$counts = count($levels);
	        foreach ($levels as $level)
	        {
	        	$chart.="{colorByPoint: false,name:"."'".$level->name.' ('.$level->range_lower.'-'.$level->range_upper.'%)'."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			$data = $checklist->level($level->id, $jimbo, $sub_county, $site, NULL, NULL, $month->annum, $month->months);
        			if($data==0)
        			{
    					$chart.= '0.00';
    					if($counter==1)
        					$chart.="";
        				else
        					$chart.=",";
    				}
    				else
    				{
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
		return view('report.spirt.period', compact('checklist', 'levels', 'sdps', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'title', 'from', 'to'));
	}
	/**
	 * Return precertification levels summary
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function precert()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Get levels
		$levels = Level::all();
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdp = NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$months = json_decode(self::getMonths($from, $to));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($checklist->id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Get sdps
		$variables = $this->sdpsTitleN($checklist->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		//	Colors to be used in the series
		$colors = array('#5cb85c', '#d6e9c6', '#f0ad4e', '#d9534f');
		$chart = "{
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1).' for - '.$title."'
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
            	if($from==$to)
		        	$chart.="'".trans('messages.for-the-year').' '.date('Y')."'";
		        else
		        	$chart.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
            $chart.="]
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Percentage'
	            },
	            min: 0,
	            max: 100
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
	        colors: ['red', '#FF7F0E', 'yellow', '#90ED7D', 'green'],
	        series: [";
        	$counts = count($levels);
	        foreach ($levels as $level)
	        {
	        	$chart.="{colorByPoint: false,name:"."'".$level->name.' ('.$level->range_lower.'-'.$level->range_upper.'%)'."'".", data:[";
	        	$data = $checklist->level($level->id, $jimbo, $sub_county, $site, $from, $toPlusOne);
    			if($data==0)
    				$chart.= '0.00';
				else
    				$chart.= $data;
        		$chart.="]";
            	if($counts==1)
					$chart.="}";
				else
					$chart.="},";
				$counts--;
	        }
	        $chart.="],
	    }";
		return view('report.spirt.precert', compact('checklist', 'levels', 'sdps', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'title', 'from', 'to'));
	}
	/**
	 * SPI-RT spider chart report - mean performance comparison across the 8 quality components
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function performance()
	{
		//	Get checklist
		$checklist = Checklist::find(Checklist::idByName('SPI-RT Checklist'));
		//	Add scorable sections to array
		$categories = array();
		foreach ($checklist->sections as $section){
			if($section->isScorable())
				array_push($categories, $section);
		}
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$months = json_decode(self::getMonths($from, $to));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($checklist->id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}
		//	Update chart title
		$variables = $this->sdpsTitleN($checklist->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$ssdps = $variables['sdps'];
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
            	foreach ($categories as $category) 
            	{
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
	        colors: ['#1F77B4', '#FF7F0E', '#2CA02C', '#D62728', '#9467BD', '#8C564B', '#E377C2', '#7F7F7F', '#BCBD22', '#17BECF', '#00ff00', '#00ff00'],
	        series: [";
	        $counter = count($months);
	        foreach ($months as $month)
    		{
    			$chart.="{name: "."'".$month->label.' '.$month->annum."', data: [";
    			$cats = count($categories);
    			foreach ($categories as $category)
    			{
   					$chart.=$category->spider($sdp, $site, $sub_county, $jimbo, NULL, NULL, $month->annum, $month->months);
   					if($cats==1)
						$chart.="";
					else
						$chart.=",";
	    			$cats--;
   				}
   				$chart.="], pointPlacement: 'on'}";
    			if($counter==1)
					$chart.="";
				else
					$chart.=",";
    			$counter--;
    		}
	   		$chart.="]
	    }";
	    return view('report.spirt.section', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'categories', 'data', 'title', 'from', 'to', 'jimbo', 'sub_county', 'site','sdps', 'sdp'));
	}
	/**
	 * Overall agreement report aggregated overtime
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function overallOvertime($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Chart title
		$title = '';
		//	Get counties
		$counties = $checklist->countiesWithData();
		//	Get all sub-counties
		$subCounties = array();
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$subCounties = County::find(Auth::user()->tier->tier)->subCounties->lists('name', 'id');
		//	Get all facilities
		$facilities = array();
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$facilities = SubCounty::find(Auth::user()->tier->tier)->facilities->lists('name', 'id');
		$sdps = array();
		//	Declare variables
		$sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$kit = NULL;
		$kit = Input::get('kit');
		if($kit==NULL)
		{
			$kit = 'KHB';
		}
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps = Facility::find($site)->points($id, $site);
		}
		if(Input::get('sub_county'))
		{
			$sub_county = Input::get('sub_county');
			$facilities = SubCounty::find($sub_county)->facilities->lists('name', 'id');
		}
		if(Input::get('county'))
		{
			$jimbo = Input::get('county');
			$subCounties = County::find($jimbo)->subCounties->lists('name', 'id');
		}	
		//	Get sdps
		$sdps = array();
		//	Percentages
		$percentages = array('<95%', '95-98%', '>98%');
		$from = Input::get('from');
		if(!$from)
			$from = date('Y-m-01');
		$to = Input::get('to');
		if(!$to)
			$to = date('Y-m-d');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$variables = $this->sdpsTitleN($id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);
		$title = $variables['title'];
		$fsdps = $variables['sdps'];
		$months = json_decode(self::getMonths($from, $to));
	    //	Percent of sites
	    $percent = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.percent-of-sites', 1).'-'.$title."'
	        },
		    subtitle: {
		        text:"; 
		        if($from==$to)
		        	$percent.="'".trans('messages.for-the-year').' '.date('Y')."'";
		        else
		        	$percent.="'".trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to."'";
		    $percent.="},
	        xAxis: {
	            categories: [";
            	foreach ($fsdps as $fsdp)
            	{
            		$name = '';
            		if($site)
            			$name = FacilitySdp::cojoin($fsdp);
            		else
            			$name = Sdp::find($fsdp)->name;
            		$percent.="'".$name."',";
            	}
	            $percent.="]
	        },
	        yAxis: {
	            title: {
	                text: '".Lang::choice('messages.percent-of-sites', 1)."'
	            }
	        },
	        credits: {
			    enabled: false
			},
			colors: ['red', 'yellow', 'green'],
			plotOptions: {
	            series: {
	            	stacking: 'normal',
	                dataLabels: {
	                    enabled: true,
	                    formatter: function() {
                            if (this.y != 0) {
                              return this.y;
                            } else {
                              return null;
                            }
                        }
	                }
	            }
	        },
	        tooltip: {
	            valueSuffix: '%',
	            shared: true
	        },
	        series: [";
	        $counts = count($percentages);
	        foreach ($percentages as $percentage)
	        {
	        	$percent.="{name:"."'".$percentage."'".", data:[";
        		$counter = count($fsdps);
        		foreach ($fsdps as $fsdp)
        		{
        			$data = $checklist->programatic($percentage, $kit, $fsdp, $sdp, $site, $sub_county, $jimbo, 0, 0, 0, $from, $toPlusOne);
        			if($data==0)
        			{
    					$percent.= '0.00';
    					if($counter==1)
        					$percent.="";
        				else
        					$percent.=",";
    				}
    				else
    				{
        				$percent.= $data;

        				if($counter==1)
        					$percent.="";
        				else
        					$percent.=",";
    				}
        			$counter--;
        		}
        		$percent.="]";
            	if($counts==1)
					$percent.="}";
				else
					$percent.="},";
				$counts--;
	        }
	        $percent.="]
	    }";
		return view('report.htc.overtime', compact('checklist', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site', 'percent', 'sdps', 'sdp', 'kit'));

	}
	/**
	*
	*	Function to return sdps, title of chart and value of N
	*
	*/
	public function sdpsTitleN($chkId, $jimbo = NULL, $sub_county = NULL, $site = NULL, $sdp = NULL, $from = NULL, $to = NULL)
	{
		$sdps = array();
		$title = '';
		$n = 0;
		$fsdps = [];
		if($jimbo || $sub_county || $site || $sdp)
		{
			if($sub_county || $site || $sdp)
			{
				if($site || $sdp)
				{
					$facility = Facility::find($site);
					if($sdp)
					{
						array_push($sdps, $sdp);
						array_push($fsdps, $sdp);
						$title = $facility->name.':<strong>'.FacilitySdp::cojoin($sdp).'</strong>';
					}
					else
					{
						$sdps = $facility->facilitySdp->lists('id');
						$title = $facility->name;
						$fsdps = $sdps;
					}
				}
				else
				{
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1);
					$sdps = FacilitySdp::whereIn('facility_id', SubCounty::find($sub_county)->facilities->lists('id'))->lists('sdp_id');
					$fsdps = FacilitySdp::whereIn('facility_id', SubCounty::find($sub_county)->facilities->lists('id'))->lists('id');
				}
			}
			else
			{
				$title = County::find($jimbo)->name.' '.Lang::choice('messages.county', 1);
				$sdps = FacilitySdp::whereIn('facility_id', Facility::whereIn('sub_county_id', County::find($jimbo)->subCounties->lists('id'))->lists('id'))->lists('sdp_id');
				$fsdps = FacilitySdp::whereIn('facility_id', Facility::whereIn('sub_county_id', County::find($jimbo)->subCounties->lists('id'))->lists('id'))->lists('id');
			}
		}
		else
		{
			$title = 'Kenya';
			$sdps = FacilitySdp::lists('sdp_id');
			$fsdps = FacilitySdp::lists('id');
		}
		$surveys = Survey::whereIn('facility_sdp_id', array_unique($fsdps))->whereBetween('date_submitted', [$from, $to])->where('checklist_id', $chkId)->lists('facility_sdp_id');
		$n = count(array_unique($surveys));
		$sdps = array_unique($sdps);
		return ['sdps' => $sdps, 'title' => $title.'(N='.$n.')'];
	}
	public function getDateRange($quarter, $fin_year)
    {
        $temp = explode('-', $fin_year); //2012-2013
        $start_date = date('');
        $end_date = date('');
        $range = array();

        if( $quarter == 1 ){

            if( $temp[0] == date('Y') ){
                $start_date = date('Y-07-01 00:00:00');
                $end_date = date('Y-09-30 23:59:59');

            }elseif( $temp[1] == date('Y') ){
                $start_date = date($temp[0] . '-07-01 00:00:00');
                $end_date = date($temp[0] . '-09-30 23:59:59');
            }

        }elseif( $quarter == 2 ){

            if( $temp[0] == date('Y') ){
                $start_date = date('Y-10-01 00:00:00');
                $end_date = date('Y-12-31 23:59:59');

            }elseif( $temp[1] == date('Y') ){
                $start_date = date($temp[0] . '-10-01 00:00:00');
                $end_date = date($temp[0] . '-12-31 23:59:59');
            }

        }elseif( $quarter == 3 ){

            if( $temp[0] == date('Y') ){
                $start_date = date($temp[1] . '-01-01 00:00:00');
                $end_date = date($temp[1] . '-03-31 23:59:59');

            }elseif( $temp[1] == date('Y') ){
                $start_date = date('Y-01-01 00:00:00');
                $end_date = date('Y-03-31 23:59:59');
            }

        }elseif( $quarter == 4 ){

            if( $temp[0] == date('Y') ){
                $start_date = date('Y-04-01 00:00:00');
                $end_date = date('Y-06-30 23:59:59');

            }elseif( $temp[1] == date('Y') ){
                $start_date = date($temp[0] . '-04-01 00:00:00 ');
                $end_date = date($temp[0] . '-06-30 23:59:59');
            }
        }

        $return['start_date'] = $start_date;
        $return['end_date'] = $end_date;

        return $return;
    }
    //	Function to return quarters given date range
    public function quarters($from, $to)
    {
    	$from = '2015-03-01';
    	$to = '2015-11-02';
    }
}
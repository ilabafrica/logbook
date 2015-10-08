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
		$sdps = array();
		//	Declare variables
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps =Sdp::whereIn('id', Facility::find($site)->ssdps())->lists('name', 'id');
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
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL || $sdp!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL|| $sdp!=NULL)
			{
				if($site!=NULL|| $sdp!=NULL)
				{
					if($sdp!=NULL)
					{
						$title = Sdp::find($sdp)->name;
						foreach (Sdp::find($sdp)->surveys as $survey) 
						{
							array_push($sdps, $survey->sdp_id);
						}
					}
					else
					{
						$title = Facility::find($site)->name.' '.Lang::choice('messages.facility', 1);;
						foreach (Facility::find($site)->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
							array_push($sdps, $sdp->sdp_id);
							}
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
		else
		{
			$title = 'Kenya';
			foreach (County::all() as $county)
			{
				foreach ($county->subCounties as $subCounty)
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
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		$months = json_decode(self::getMonths($from, $to));
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
			colors: ['#00CCFF', '#0066FF', '#0000FF'],
			plotOptions: {
	            dataLabels:{
	            	enabled:true
	            }
	        },
	        tooltip: {
	            valueSuffix: '%'
	        },
	        series: [";
	        $counts = count($sdps);
	        foreach ($percentages as $percentage)
	        {
	        	$percent.="{name:"."'".$percentage."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			$percent.="{name:"."'".$month->label.' '.$month->annum."'".", y:";
        			$data = $checklist->positivePercent($percentage, $sdps, $site, $sub_county, $jimbo, $month->annum, $month->months);
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
	        $percent.="],
		        drilldown: {
		            series: [";
		            foreach ($percentages as $percentage)
	        		{
	        			foreach ($months as $month)
        				{
        					$sticker = $percentage." - ".$month->label." ".$month->annum;
        					$combined = $percentage.'_'.$month->months.'_'.$month->annum;
        					$percent.="{name:"."'".$sticker."', "."id:"."'".$combined."'".", data:[";
        					foreach ($checklist->sdpPosPercent($combined, $sdps, $site, $sub_county, $jimbo) as $sdp=>$per)
        					{
        						$percent.="["."'".$sdp."'".", ".$per."],";
        					}
        					$percent.="]},";
        				}
	        		}
	            $percent.="]
	        }
	    }";
		return view('report.htc.positive', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site','sdps', 'sdp', 'percent'));
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
		$sdps = array();
		//	Declare variables
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps =Sdp::whereIn('id', Facility::find($site)->ssdps())->lists('name', 'id');
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
		$sdps = array();
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL || $sdp!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL|| $sdp!=NULL)
			{
				if($site!=NULL|| $sdp!=NULL)
				{
					if($sdp!=NULL)
					{
						$title = Sdp::find($sdp)->name;
						foreach (Sdp::find($sdp)->surveys as $survey) 
						{
							array_push($sdps, $survey->sdp_id);
						}
					}
					else
					{
						$title = Facility::find($site)->name.' '.Lang::choice('messages.facility', 1);;
						foreach (Facility::find($site)->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
							array_push($sdps, $sdp->sdp_id);
							}
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
		else
		{
			$title = 'Kenya';
			foreach (County::all() as $county)
			{
				foreach ($county->subCounties as $subCounty)
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
		return view('report.htc.agreement', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site', 'sdps','sdp'));
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
		$sdps = array();
		//	Declare variables
		$sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('sdp'))
		{
			$sdp = Input::get('sdp');
		}
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		    $sdps =Sdp::whereIn('id', Facility::find($site)->ssdps())->lists('name', 'id');
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
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL || $sdp!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL|| $sdp!=NULL)
			{
				if($site!=NULL|| $sdp!=NULL)
				{
					if($sdp!=NULL)
					{
						$title = Sdp::find($sdp)->name;
						foreach (Sdp::find($sdp)->surveys as $survey) 
						{
							array_push($sdps, $survey->sdp_id);
						}
					}
					else
					{
						$title = Facility::find($site)->name.' '.Lang::choice('messages.facility', 1);;
						foreach (Facility::find($site)->surveys as $survey) 
						{
							foreach ($survey->sdps as $sdp) 
							{
							array_push($sdps, $sdp->sdp_id);
							}
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
		else
		{
			$title = 'Kenya';
			foreach (County::all() as $county)
			{
				foreach ($county->subCounties as $subCounty)
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
			colors: ['#00CCFF', '#0066FF', '#0000FF'],
			plotOptions: {
	            dataLabels:{
	            	enabled:true
	            }
	        },
	        tooltip: {
	            valueSuffix: '%'
	        },
	        series: [";
	        $counts = count($sdps);
	        foreach ($percentages as $percentage)
	        {
	        	$percent.="{name:"."'".$percentage."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month)
        		{
        			$percent.="{name:"."'".$month->label.' '.$month->annum."'".", y:";
        			$data = $checklist->overallAgreement($percentage, $sdps, $site, $sub_county, $jimbo, $month->annum, $month->months);
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
	        $percent.="],
		        drilldown: {
		            series: [";
		            foreach ($percentages as $percentage)
	        		{
	        			foreach ($months as $month)
        				{
        					$sticker = $percentage." - ".$month->label." ".$month->annum;
        					$combined = $percentage.'_'.$month->months.'_'.$month->annum;
        					$percent.="{name:"."'".$sticker."', "."id:"."'".$combined."'".", data:[";
        					foreach ($checklist->sdpOverAgreement($combined, $sdps, $site, $sub_county, $jimbo) as $sdp=>$per)
        					{
        						$percent.="["."'".$sdp."'".", ".$per."],";
        					}
        					$percent.="]},";
        				}
	        		}
	            $percent.="]
	        }
	    }";
		return view('report.htc.overall', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'from', 'to', 'jimbo', 'sub_county', 'site', 'percent', 'sdps', 'sdp'));

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
		//dd(Input::all());
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
		$sdps = array();
        $sdp =NULL;
		$site = NULL;
		$sub_county = NULL;
		$jimbo = NULL;
		$from = Input::get('from');
		$to = Input::get('to');
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
		    $sdps =Sdp::whereIn('id', Facility::find($site)->ssdps())->lists('name', 'id');
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
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL || $sdp!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL || $sdp!=NULL)
			{
				if($site!=NULL || $sdp!=NULL)
				{
					if($sdp!=NULL)
					{
						$title = Sdp::find($sdp)->name.' '.'for'.' '.Facility::find($site)->name;
					}
					else
					{
						$title = Facility::find($site)->name;
				    }
				}				
				else
				{
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1);
				}
			}
			else
			{
				$cc = County::find($jimbo);
				$title = $cc->name.' '.Lang::choice('messages.county', 1);				
			}
		}
		else
		{
			$title = 'Kenya';
		}
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

	        			$data = Answer::find(Answer::idByName($option))->column($category->id, $jimbo, $sub_county, $site, $sdp, $from, $toPlusOne);

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
		$counties = County::lists('name', 'id');
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
		$from = date('Y-08-d');
		$to = Input::get('to');
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
		    $sdps =Sdp::whereIn('id', Facility::find($site)->ssdps())->lists('name', 'id');
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
		if($jimbo!=NULL || $sub_county!=NULL || $site!=NULL || $sdp!=NULL)
		{
			if($sub_county!=NULL || $site!=NULL || $sdp!=NULL)
			{
				if($site!=NULL || $sdp!=NULL)
				{
				 if($sdp!=NULL)
				{
					$title = Sdp::find($sdp)->name.' '.'for'.' '.Facility::find($site)->name;
				}
				else 
				{
					$title = Facility::find($site)->name;
				}
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

	    $level = $checklist->level($categories, $jimbo, $sub_county, $site, $sdp);
	    return view('report.spirt.spider', compact('checklist', 'chart', 'counties', 'subCounties', 'facilities', 'categories', 'data', 'title', 'from', 'to', 'jimbo', 'sub_county', 'site','sdps', 'sdp', 'level'));

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
		$today = "'".date("Y-m-d")."'";
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		}
		if(Input::get('sub_county'))
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
					
					$n = $checklist->ssdps($from, $toPlusOne, null, null, $site);
					$title = Facility::find($site)->name.'(N='.$n.')';
				}
				else
				{					
					$n = $checklist->ssdps($from, $toPlusOne, null, $sub_county, null);				
					$title = SubCounty::find($sub_county)->name.' '.Lang::choice('messages.sub-county', 1).'(N='.$n.')';
				}
			}
			else
			{
				$n = $checklist->ssdps($from, $toPlusOne, $jimbo, null, null);
				$title = County::find($jimbo)->name.' '.Lang::choice('messages.county', 1).'(N='.$n.')';
			}
		}
		else
		{

			if(strtotime($from)===strtotime($today))
				$n = $checklist->ssdps();
			else
				$n = $checklist->ssdps($from, $toPlusOne, null, null, null);
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
		if(Input::get('sub_county'))
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
		return view('report.me.snapshot', compact('checklist', 'columns', 'options', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'from', 'to', 'toPlusOne', 'title'));
	}

	public function breakdown()
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
		if(Input::get('sub_county'))
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
		return view('report.me.breakdown', compact('checklist', 'columns', 'options', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'from', 'to', 'toPlusOne', 'title','domain'));
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
		$sdps = Sdp::all();
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
		$from = date('Y-08-d');
		$to = Input::get('to');
		$toPlusOne = date_add(new DateTime($to), date_interval_create_from_date_string('1 day'));
		//	Get facility
		//$facility = Facility::find(2);
		if(Input::get('facility'))
		{
			$site = Input::get('facility');
		}
		if(Input::get('sub_county'))
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
	            		$chart.="'".$sdp->name."',";
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
	        			$data = $level->level($checklist->id, $jimbo, $sub_county, $site, $sdp->id, $from, $toPlusOne);
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
		return view('report.spirt.level', compact('checklist', 'levels', 'sdps', 'chart', 'counties', 'subCounties', 'facilities', 'jimbo', 'sub_county', 'site', 'title', 'from', 'to'));
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
	/**
	 * Show the application landing page upon successful signin.
	 *
	 * @return Response
	 */
	public function dashboard()
	{
		//	Get complete sites
		$counter = 0;
		$facilities = Survey::lists('facility_id');
		$htc = Checklist::idByName('HTC Lab Register (MOH 362)');
		$me = Checklist::idByName('M & E Checklist');
		$spi = Checklist::idByName('SPI-RT Checklist');
		foreach ($facilities as $key => $value)
		{
			//	Variables
			$facility = Facility::find($value);
			if(($facility->sdps($htc) == $facility->sdps($me)) && ($facility->sdps($me) == $facility->sdps($spi)))
				$counter++;
		}
		//	Get checklists
		$checklists = Checklist::all();
		//	Get dates and months
		$from = Input::get('from');
		$to = Input::get('to');
		$months = json_decode(self::getMonths($from, $to));

	    //	Calculation of "complete"
	    $htc_me = 0;
       	$htc_spirt = 0;
       	$spirt_me = 0;
       	//        Facilities
       	$facilities = Facility::all();
       	//        Complete counts
       	$complete = 0;
       	$all = 0;
       	$pmtcts = 0;
       	$pmtctMeSpi = 0;
       	//	PMTCT
       	$pmtct = Sdp::idByName('PMTCT');
       	foreach ($facilities as $facility)
       	{
           	$bothMeSpirt = array();
           	$spirt_sdps = $facility->ssdps($spi);
           	$me_sdps = $facility->ssdps($me);
           	$htc_sdps = $facility->ssdps($htc);
           	//	get survey-sdp ids for use in getting PMTCT records
           	if($facility->sdps($spi, $pmtct) == $facility->sdps($me, $pmtct))
           	{
           		$pmtcts++;
           	}
           	if(($facility->sdps($spi, $pmtct) == $facility->sdps($me, $pmtct)) && ($facility->sdps($me, $pmtct) == $facility->sdps($htc, $pmtct)))
           	{
           		$pmtctMeSpi++;
           	}
           	foreach ($me_sdps as $me_sdp)
           	{
               	if(in_array($me_sdp, $spirt_sdps))
                {
                    $complete++;
                    $spirt_me++;
                    $bothMeSpirt = array_merge($bothMeSpirt, [$me_sdp]);
                }
           	}
           	foreach ($htc_sdps as $htc_sdp)
            {
                if(in_array($htc_sdp, $me_sdps))
                    $htc_me++;
                if(in_array($htc_sdp, $bothMeSpirt))
                    $all++;
            }
           	foreach ($htc_sdps as $htc_sdp)
            {
                if(in_array($htc_sdp, $spirt_sdps))
                    $htc_spirt++;
            }
       	}
       	//	End calculation of 'complete'
		$drill = "{
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: '".Lang::choice('messages.complete-check', 1)."'
	        },
	        yAxis: {
	            title: {
	                text: '".Lang::choice('messages.complete-sdp', 1)."'
	            }
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
	                    enabled: true
	                }
	            }
	        },

	        series: [{
	            name: '".Lang::choice('messages.complete', 1)."',
	            colorByPoint: true,
	            data: [{
	                name: '".Lang::choice('messages.sdp', 2)."',
	                y: 206,
	                drilldown: 'complete'
	            }]
	        }],
	        drilldown: {
	            series: [{
	                id: 'complete',
	                data: [";
	                $count = count($checklists);
	                foreach ($checklists as $checklist)
	                {
	                	$drill.="["."'".$checklist->name."'".", ".$checklist->ssdps()."]";
	                	if($count==1)
	    					$drill.="";
	    				else
	    					$drill.=",";
	    				$count--;
	                }
	                $drill.="]
	            }]
	        }
	    }";
	    //	Pie chart for county submissions
	    $htc_pie = "{
	        chart: {
	            type: 'pie'
	        },
	        title: {
	            text: 'HTC Lab Register (MoH 362)'
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
	            	foreach (Checklist::find($htc)->distCount($htc) as $key)
	            	{
	            		$county = County::find($key);
	            		$htc_pie.="{
			                name: '".$county->name."',
			                y: ".$county->submissions($htc).",
			                drilldown: '".$county->id."'
			            },";
	            	}
	            	$htc_pie.="
	            ]
	        }],
	        drilldown: {
	            series: [";
	            foreach (Checklist::find($htc)->distCount() as $key)
            	{
            		$county = County::find($key);
            		$htc_pie.="{
		                id: '".$county->id."',
		                name: 'Total',
		                data: [";
		                foreach ($county->subCounties as $subCounty)
		                {
		                	$htc_pie.="{
			                    name: '".$subCounty->name."',
			                    y: ".$subCounty->submissions($htc).",
			                    drilldown: '".$subCounty->name."'
			                },";
		                }
		                $htc_pie.="]
		            },";
            	}
            	foreach (Checklist::find($htc)->distCount() as $key)
            	{
            		$county = County::find($key);
	                foreach ($county->subCounties as $subCounty)
	                {
	                	$htc_pie.="{
			                id: '".$subCounty->name."',
			                name: 'Total',
			                data: [";
		                	foreach ($subCounty->facilities as $facility)
		                	{
		                		$htc_pie.="{
				                    name: '".$facility->name."',
				                    y: ".$facility->sdps($htc).",
				                    drilldown: '".$facility->id.'_'.$subCounty->id."'
				                },";
				            }
				            $htc_pie.="]
			            },";
	                }
            	}
            	foreach (Checklist::find($htc)->distCount() as $key)
            	{
            		$county = County::find($key);
	                foreach ($county->subCounties as $subCounty)
	                {
	                	foreach ($subCounty->facilities as $facility)
		                {
		                	$htc_pie.="{
				                id: '".$facility->id.'_'.$subCounty->id."',
				                data: [";
			                	foreach ($facility->ssdps($htc) as $ssdp)
			                	{
			                		$sdp = Sdp::find($ssdp);
			                		$htc_pie.="{
					                    name: '".$sdp->name."',
					                    y: ".$sdp->submissions($facility->id, $htc).",
					                    drilldown: '".$sdp->name."'
					                },";
					            }
					            $htc_pie.="]
				            },";
			        	}
	                }
            	}
            	$htc_pie.="]
	        }
	    }";
	    //	M&E pie
	    $me_pie = "{
	        chart: {
	            type: 'pie'
	        },
	        title: {
	            text: 'M&E Checklist'
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
	            	foreach (Checklist::find($me)->distCount($me) as $key)
	            	{
	            		$county = County::find($key);
	            		$me_pie.="{
			                name: '".$county->name."',
			                y: ".$county->submissions($me).",
			                drilldown: '".$county->id."'
			            },";
	            	}
	            	$me_pie.="
	            ]
	        }],
	        drilldown: {
	            series: [";
	            foreach (Checklist::find($me)->distCount() as $key)
            	{
            		$county = County::find($key);
            		$me_pie.="{
		                id: '".$county->id."',
		                name: 'Total',
		                data: [";
		                foreach ($county->subCounties as $subCounty)
		                {
		                	$me_pie.="{
			                    name: '".$subCounty->name."',
			                    y: ".$subCounty->submissions($me).",
			                    drilldown: '".$subCounty->name."'
			                },";
		                }
		                $me_pie.="]
		            },";
            	}
            	foreach (Checklist::find($me)->distCount() as $key)
            	{
            		$county = County::find($key);
	                foreach ($county->subCounties as $subCounty)
	                {
	                	$me_pie.="{
			                id: '".$subCounty->name."',
			                name: 'Total',
			                data: [";
		                	foreach ($subCounty->facilities as $facility)
		                	{
		                		$me_pie.="{
				                    name: '".$facility->name."',
				                    y: ".$facility->sdps($me).",
				                    drilldown: '".$facility->id.'_'.$subCounty->id."'
				                },";
				            }
				            $me_pie.="]
			            },";
	                }
            	}
            	foreach (Checklist::find($me)->distCount() as $key)
            	{
            		$county = County::find($key);
	                foreach ($county->subCounties as $subCounty)
	                {
	                	foreach ($subCounty->facilities as $facility)
		                {
		                	$me_pie.="{
				                id: '".$facility->id.'_'.$subCounty->id."',
				                data: [";
			                	foreach ($facility->ssdps($me) as $ssdp)
			                	{
			                		$sdp = Sdp::find($ssdp);
			                		$me_pie.="{
					                    name: '".$sdp->name."',
					                    y: ".$sdp->submissions($facility->id, $me).",
					                    drilldown: '".$sdp->name."'
					                },";
					            }
					            $me_pie.="]
				            },";
			        	}
	                }
            	}
            	$me_pie.="]
	        }
	    }";
	    //	SPI-RT pie
	    $spi_pie = "{
	        chart: {
	            type: 'pie'
	        },
	        title: {
	            text: 'SPI-RT Checklist'
	        },
	        xAxis: {
	            type: 'category'
	        },

	        legend: {
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
	        credits: {
	            enabled: false
	        },
	        series: [{
	            name: 'Total',
	            colorByPoint: true,
	            data: [";
	            	foreach (Checklist::find($spi)->distCount($spi) as $key)
	            	{
	            		$county = County::find($key);
	            		$spi_pie.="{
			                name: '".$county->name."',
			                y: ".$county->submissions($spi).",
			                drilldown: '".$county->id."'
			            },";
	            	}
	            	$spi_pie.="
	            ]
	        }],
	        drilldown: {
	            series: [";
	            foreach (Checklist::find($spi)->distCount() as $key)
            	{
            		$county = County::find($key);
            		$spi_pie.="{
		                id: '".$county->id."',
		                name: 'Total',
		                data: [";
		                foreach ($county->subCounties as $subCounty)
		                {
		                	$spi_pie.="{
			                    name: '".$subCounty->name."',
			                    y: ".$subCounty->submissions($spi).",
			                    drilldown: '".$subCounty->name."'
			                },";
		                }
		                $spi_pie.="]
		            },";
            	}
            	foreach (Checklist::find($spi)->distCount() as $key)
            	{
            		$county = County::find($key);
	                foreach ($county->subCounties as $subCounty)
	                {
	                	$spi_pie.="{
			                id: '".$subCounty->name."',
			                name: 'Total',
			                data: [";
		                	foreach ($subCounty->facilities as $facility)
		                	{
		                		$spi_pie.="{
				                    name: '".$facility->name."',
				                    y: ".$facility->sdps($spi).",
				                    drilldown: '".$facility->id.'_'.$subCounty->id."'
				                },";
				            }
				            $spi_pie.="]
			            },";
	                }
            	}
            	foreach (Checklist::find($spi)->distCount() as $key)
            	{
            		$county = County::find($key);
	                foreach ($county->subCounties as $subCounty)
	                {
	                	foreach ($subCounty->facilities as $facility)
		                {
		                	$spi_pie.="{
				                id: '".$facility->id.'_'.$subCounty->id."',
				                data: [";
			                	foreach ($facility->ssdps($spi) as $ssdp)
			                	{
			                		$sdp = Sdp::find($ssdp);
			                		$spi_pie.="{
					                    name: '".$sdp->name."',
					                    y: ".$sdp->submissions($facility->id, $spi).",
					                    drilldown: '".$sdp->name."'
					                },";
					            }
					            $spi_pie.="]
				            },";
			        	}
	                }
            	}
            	$spi_pie.="]
	        }
	    }";
	    //	Combination chart
	    $combination = "{
	        title: {
	            text: '".Lang::choice('messages.sdp-comparison-overtime', 1)."'
	        },
	        yAxis: {
	            title: {
	                text: '".Lang::choice('messages.complete-sdp', 1)."'
	            }
	        },
	        xAxis: {
	            categories: [";
		            $count = count($months);
	            	foreach ($months as $month) {
	    				$combination.= "'".$month->label.' '.$month->annum;
	    				if($count==1)
	    					$combination.="' ";
	    				else
	    					$combination.="' ,";
	    				$count--;
	    			}
	            $combination.="]
	        },

	        credits: {
	            enabled: false
	        },
	        series: [";
	        $counts = count($checklists);
	        foreach ($checklists as $checklist) {
	        	$combination.="{type:'column',name:"."'".$checklist->name."'".", data:[";
        		$counter = count($months);
        		foreach ($months as $month) {
        			$data = $checklist->ssdps(null, null, null, null, null, null, $month->annum, $month->months);
        			if($data==0){
        					$chart.= '0.00';
        					if($counter==1)
            					$combination.="";
            				else
            					$combination.=",";
    				}
    				else{
        				$combination.= $data;

        				if($counter==1)
        					$combination.="";
        				else
        					$combination.=",";
    				}
        			$counter--;
        		}
        		$combination.="]";
            	if($counts==1)
					$combination.="}";
				else
					$combination.="},";
				$counts--;
	        }
	        $combination.=",{
	            type: 'spline',
	            name: 'Average',
	            data: [177, 26, 3],
	            marker: {
	                lineWidth: 2,
	                lineColor: Highcharts.getOptions().colors[3],
	                fillColor: 'white'
	            }
	        }]
	    }";
		return view('dashboard', compact('drill', 'pie', 'combination', 'htc_pie', 'me_pie', 'spi_pie'));
	}
}
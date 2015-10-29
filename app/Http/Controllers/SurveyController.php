<?php namespace App\Http\Controllers;
set_time_limit(0); //60 seconds = 1 minute
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\User;
use App\Models\Question;
use App\Models\Survey;
use App\Models\SurveyData;
use App\Models\Facility;
use App\Models\Sdp;
use App\Models\SurveyQuestion;
use App\Models\Affiliation;
use App\Models\Algorithm;
use App\Models\AuditType;
use App\Models\TestKit;
use App\Models\MeInfo;
use App\Models\SpirtInfo;
use App\Models\SurveyScore;
use App\Models\Answer;
use App\Models\SurveySdp;
use App\Models\HtcSurveyPage;
use App\Models\HtcSurveyPageQuestion;
use App\Models\HtcSurveyPageData;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\Cadre;

use Illuminate\Http\Request;
use Response;
use Auth;
use Input;
use Lang;
use App;
use Excel;
use Jenssegers\Date\Date as Carbon;
use DB;
class SurveyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//	Get all checklists
		$checklists = Checklist::all();
		//	Get all users
		$users = User::all();
		//	return view with the data
		$county = null;
		$subCounty = null;
		$surveys = null;
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$county = County::find(Auth::user()->tier->tier);
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$subCounty = SubCounty::find(Auth::user()->tier->tier);
		//dd($county);
		if($county || $subCounty){
			foreach ($checklists as $checklist)
			{
				$surveys[$checklist->id] = $checklist->surveys()->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
																->join('survey_sdps', 'surveys.id', '=', 'survey_sdps.survey_id');
				if($subCounty)
				{
					$surveys[$checklist->id] = $surveys[$checklist->id]->where('facilities.sub_county_id', $subCounty->id)->get();
				}
				if($county)
				{
					$surveys[$checklist->id] = $surveys[$checklist->id]->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
																  ->where('sub_counties.county_id', $county->id)->get();
				}
			}
		}
		return view('survey.index', compact('checklists', 'users', 'county', 'subCounty', 'surveys'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		//	Get specific checklist
		$checklist = Checklist::find($id);
		//	Get list of facilities
		$facilities = Facility::lists('name', 'id');
		//	Get list of service delivery points
		$sdps = Sdp::lists('name', 'id');
		//	Get list of algorithms
		$algorithms = Algorithm::lists('name', 'id');
		//	Get list of affiliations
		$affiliations = Affiliation::lists('name', 'id');
		//	Get list of audit-types
		$auditTypes = AuditType::lists('name', 'id');
		//	Get list of test kits
		$kits = TestKit::lists('name', 'id');
		return view('survey.create', compact('checklist', 'facilities', 'sdps', 'algorithms', 'affiliations', 'auditTypes', 'kits'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//dd(Input::all());
		$checklist_id = Input::get('checklist_id');
		$facility_id = Input::get('facility');
		$qa_officer = Input::get('qa_officer');
		$longitude = Input::get('longitude');
		$latitude = Input::get('latitude');
		$comments = Input::get('comments');
		$sdp_id = Input::get('sdp');
		$affiliation = NULL;
		$audit_type = NULL;
		$algorithm = NULL;
		$screening = NULL;
		$confirmatory = NULL;
		$tie_breaker = NULL;
		if($checklist_id == Checklist::idByName('SPI-RT Checklist'))
			$affiliation = Input::get('affiliation');
		if($checklist_id == Checklist::idByName('M & E Checklist')){
			$audit_type = Input::get('audit_type');
			$algorithm = Input::get('algorithm');
			$screening = Input::get('screening');
			$confirmatory = Input::get('confirmatory');
			$tie_breaker = Input::get('tie_breaker');
		}
		//	Check if survey exists
		$survey = Survey::where('checklist_id', $checklist_id)
						->where('facility_id', $facility_id)
						->where('qa_officer', $qa_officer)
						->where('sdp_id', $sdp_id)
						->first();
		if(count($survey) == 0){
			$survey = new Survey;
			$survey->checklist_id = $checklist_id;
			$survey->facility_id = $facility_id;
			$survey->qa_officer = $qa_officer;
			$survey->latitude = $latitude;
			$survey->longitude = $longitude;
			$survey->comment = $comments;
			$survey->sdp_id = $sdp_id;
			$survey->save();
		}
		//	ME info
		if($checklist_id == Checklist::idByName('M & E Checklist')){
			$me_info = $survey->me;
			if(count($me_info) == 0){
				$me_info = new MeInfo;
				$me_info->survey_id = $survey->id;
				$me_info->audit_type_id = $audit_type;
				$me_info->algorithm_id = $algorithm;
				$me_info->screening = $screening;
				$me_info->confirmatory = $confirmatory;
				$me_info->tie_breaker = $tie_breaker;
				$me_info->save();
			}
		}
		//	SPI-RT info
		if($checklist_id == Checklist::idByName('SPI-RT Checklist')){
			$spirt_info = $survey->spirt;
			if(count($spirt_info) == 0){
				$spirt_info = new SpirtInfo;
				$spirt_info->survey_id = $survey->id;
				$spirt_info->affiliation_id = $affiliation;
				$spirt_info->save();
			}
		}
		foreach (Input::all() as $key => $value) {
			if((stripos($key, 'token') !==FALSE) || (stripos($key, 'checklist') !==FALSE) || (stripos($key, 'qa') !==FALSE) || (stripos($key, 'audit') !==FALSE))
				continue;
			else if((stripos($key, 'date') !==FALSE) || (stripos($key, 'radio') !==FALSE) || (stripos($key, 'textfield') !==FALSE) || (stripos($key, 'textarea') !==FALSE)){
				$fieldId = $this->strip($key);
				//	Check if survey-question exists before saving
				$surveyQuestion = SurveyQuestion::where('survey_id', $survey->id)->where('question_id', $fieldId)->first();
				//	Create survey-question
				if(!$surveyQuestion){
					$surveyQuestion = new SurveyQuestion;
					$surveyQuestion->survey_id = $survey->id;
					$surveyQuestion->question_id = $fieldId;
					$surveyQuestion->save();
				}
				//	Check if data already exists
				$surveyData = SurveyData::where('survey_question_id', $surveyQuestion->id)->first();
				//	Save survey data
				if(!$surveyData){
					$surveyData = new SurveyData;
					$surveyData->survey_question_id = $surveyQuestion->id;
					$surveyData->answer = $value;
					$surveyData->save();
				}				
				//	Check if scorable
				if($responses = Question::find($surveyQuestion->question_id)->answers)
				{
					$answers = array();
					foreach ($responses as $response) 
					{
						if($response->score)
							array_push($answers, $response->id);
					}
					if(count($answers)>0)
					{
						//	Check if score already exists
						$surveyScore = SurveyScore::where('survey_question_id', $surveyQuestion->id)->first();
						if(!$surveyScore)
						{
							$surveyScore = new SurveyScore;
							$surveyScore->survey_question_id = $surveyQuestion->id;
							$surveyScore->score = Answer::find(Answer::idByName($value))->score;
							$surveyScore->save();
						}
					}
				}
			}
		}
		return redirect('survey');
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function listing($id)
	{
		//	Get checklist
		$checklist_id= $id;
		$checklist = Checklist::find($id);
		$county = null;
		$subCounty = null;
		$surveys = null;
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$county = County::find(Auth::user()->tier->tier);
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$subCounty = SubCounty::find(Auth::user()->tier->tier);
		if($county || $subCounty)
		{
			$surveys = $checklist->surveys()->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
						->join('survey_sdps', 'surveys.id', '=', 'survey_sdps.survey_id');
			if($subCounty)
			{
				$surveys = $surveys->where('facilities.sub_county_id', $subCounty->id)->get();
			}
			else
			{
				$surveys = $surveys->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
							  	   ->where('sub_counties.county_id', $county->id)->get();
			}
		}
		else
		{
			$surveys = $checklist->surveys;
		}
		return view('survey.list', compact('checklist','checklist_id', 'surveys'));
	}

	/**
	 * Update data month
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function dataMonth($id = 0)
	{
		//	Get survey
		if($id == 0)
		{
			$id = Input::get('dataMonth');
		}
		$survey = Survey::find($id);
		$date = Input::get('newest_date');
		//	Do the operation - check if months are similar (date_submitted vs newest date)
		$newest_date = Carbon::parse($date);
		$date_submitted = Carbon::parse($survey->date_submitted);
		if($date_submitted->month != $newest_date->month)
		{
			$data_month = $newest_date->firstOfMonth();
			$survey->data_month = $data_month;
			$survey->save();
		}
		//	Redirect
		$url = session('SOURCE_URL');
		return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-updated', 1));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//	Get survey
		$survey = Survey::find($id);
		$sdps = Sdp::lists('name', 'id');
		return view('survey.show', compact('survey', 'checklist_id', 'sdps'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//	Get survey
		$survey = Survey::find($id);
		return view('survey.edit', compact('survey'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$survey = Survey::findOrFail($id);
		$survey->comment = Input::get('comments');
        $survey->save();
        //$url = session('SOURCE_URL');
		/*$checklist_id = Input::get('checklist_id');
		$facility_id = Input::get('facility');
		$qa_officer = Input::get('qa_officer');
		$longitude = Input::get('longitude');
		$latitude = Input::get('latitude');
		$comments = Input::get('comments');
		$sdp_id = Input::get('sdp');
		//	Check if survey exists
		$survey = Survey::find($id);
		if($survey->isEmpty()){
			$survey = new Survey;
			$survey->checklist_id = $checklist_id;
			$survey->facility_id = $facility_id;
			$survey->qa_officer = $qa_officer;
			$survey->latitude = $latitude;
			$survey->longitude = $longitude;
			$survey->comment = $comments;
			$survey->sdp_id = $sdp_id;
			$survey->save();
		}
		foreach (Input::all() as $key => $value) {
			if((stripos($key, 'token') !==FALSE) || (stripos($key, 'checklist') !==FALSE) || (stripos($key, 'qa') !==FALSE))
				continue;
			else if((stripos($key, 'date') !==FALSE) || (stripos($key, 'radio') !==FALSE) || (stripos($key, 'textfield') !==FALSE) || (stripos($key, 'textarea') !==FALSE)){
				$fieldId = $this->strip($key);
				//	Check if survey-question exists before saving
				$surveyQuestion = SurveyQuestion::where('survey_id', $survey->id)->where('question_id', $fieldId)->first();
				//	Create survey-question
				if(!$surveyQuestion){
					$surveyQuestion = new SurveyQuestion;
					$surveyQuestion->survey_id = $survey->id;
					$surveyQuestion->question_id = $fieldId;
					$surveyQuestion->save();
				}
				//	Check if data already exists
				$surveyData = SurveyData::where('survey_question_id', $surveyQuestion->id)->first();
				//	Save survey data
				if(!$surveyData){
					$surveyData = new SurveyData;
					$surveyData->survey_question_id = $surveyQuestion->id;
					$surveyData->answer = $value;
					$surveyData->save();
				}
				//	Check if scorable
				if($responses = Question::find($surveyQuestion->question_id)->answers)
				{
					$answers = array();
					foreach ($responses as $response) 
					{
						if((float)$response->score>0)
							array_push($answers, $response->id);
					}
					if(count($answers)>0)
					{
						//	Check if score already exists
						$surveyScore = SurveyScore::where('survey_question_id', $surveyQuestion->id)->first();
						if(!$surveyScore)
						{
							$surveyScore = new SurveyScore;
							$surveyScore->survey_question_id = $surveyQuestion->id;
							$surveyScore->score = Answer::find(Answer::idByName($value))->score;
							$surveyScore->save();
						}
					}
				}
			}
		}*/
		return redirect('survey');
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
	 * Remove the specified resource from storage (soft delete).
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		//	soft delete the survey
		$survey = Survey::find($id);
		//	m&e and spirt
		if($survey->checklist->name == 'HTC Lab Register (MOH 362)')
		{
			//	Delete questions and data first
			foreach ($survey->sdp as $ssdp)
			{
				foreach ($ssdp->pages as $page)
				{
					foreach ($page->questions as $question)
					{
						if($question->sd)
							$question->data->delete();
					}
					$page->questions()->delete();
				}
				$ssdp->pages()->delete();
			}
			$survey->delete();
		}
		else
		{
			foreach ($survey->sdp as $ssdp)
			{
				foreach ($ssdp->sqs as $question)
				{
					if($question->sd)
						$question->sd->delete();
				}
				$ssdp->sqs()->delete();
			}
			$survey->delete();
		}
		//	Redirect
		$url = session('SOURCE_URL');
		return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-deleted', 1));
	}
	/**
	 * Show summaries as desired
	 *
	 * @param  int  $id of checklist
	 * @return Response
	 */
	public function summary($id)
	{
		//	Get specific checklist
		$checklist = Checklist::find($id);
		$sections = array();		
		foreach ($checklist->sections as $section) {
			$questions = array();
			foreach ($section->questions as $question) {
				if($question->answers->count()>0)
					array_push($questions, $question);
			}
			if(count($questions)>0)
				array_push($sections, $section);
		}
		return view('survey.summary', compact('checklist', 'sections'));
	}
	/**
	 * Show data collection summaries as desired
	 *
	 * @param  int  $id of checklist
	 * @return Response
	 */
	public function collection($id)
	{
		//	Get specific checklist
		$checklist = Checklist::find($id);
		//	Get unique QA Officers
		$qa = Survey::select('qa_officer')
					->where('checklist_id', $id)
					->groupBy('qa_officer');
		$county = null;
		$subCounty = null;
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$county = County::find(Auth::user()->tier->tier);
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$subCounty = SubCounty::find(Auth::user()->tier->tier);
		if($county || $subCounty)
		{
			if($subCounty)
			{
				$qa = $qa->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
						 ->where('facilities.sub_county_id', $subCounty->id);
			}
			else
			{
				$qa = $qa->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
						 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
						 ->where('sub_counties.county_id', $county->id);
			}
		}
		$qa = $qa->get();
		return view('survey.collection', compact('checklist', 'qa'));
	}
	/**
	 * Show participating facilities summaries as desired
	 *
	 * @param  int  $id of checklist
	 * @return Response
	 */
	public function participant($id)
	{
		//	Get specific checklist
		$checklist = Checklist::find($id);
		$facilities = Facility::all();
		$county = null;
		$subCounty = null;
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$county = County::find(Auth::user()->tier->tier);
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$subCounty = SubCounty::find(Auth::user()->tier->tier);
		if($county || $subCounty)
		{
			if($subCounty)
			{
				$facilities = $subCounty->facilities;
			}
			else
			{
				$facilities = $county->facilities();
			}
		}
		return view('survey.participant', compact('checklist', 'facilities'));
	}

	/**
	 * Show participating sdps per facility
	 *
	 * @param  int  $id of checklist
	 * @return Response
	 */
	public function sdp($id)
	{
		//	Get specific checklist
		$checklist = Checklist::find($id);
		$facilities = Facility::all();
		$county = null;
		$subCounty = null;
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$county = County::find(Auth::user()->tier->tier);
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$subCounty = SubCounty::find(Auth::user()->tier->tier);
		if($county || $subCounty)
		{
			if($subCounty)
			{
				$facilities = $subCounty->facilities;
			}
			else
			{
				$facilities = $county->facilities();
			}
		}
		$surveys = array();
		$total = array();
		foreach ($facilities as $facility)
		{
			$sdps = array();
			$total[$facility->id] = 0;
			$surveys[$facility->id] = $facility->surveys->where('checklist_id', $checklist->id)->lists('id');
			foreach ($surveys[$facility->id] as $key)
			{
				$sdps[$key] = Survey::find($key)->sdps->lists('sdp_id');
				$total[$facility->id] = count($sdps[$key]);
			}
		}
		return view('survey.sdp', compact('checklist', 'facilities', 'surveys', 'sdps', 'total'));
	}
	/**
	 * Remove the specified begining of text to get Id alone.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function strip($field)
	{
		if(($pos = strpos($field, '_')) !== FALSE)
		return substr($field, $pos+1);
	}
	/**
	 * Function to call api to import data
	 *
	 * @param  int  $id of checklist
	 * @return Response
	 */
	public function api($id = 0)
	{
		//	Get specific checklist
		if($id == 0)
		{
			$id = Input::get('checklist_id');
		}
		$date_from = Input::get('from');
		$date_to = Input::get('to');
		dd($date_from);
		$checklist = Checklist::find($id);
		if($checklist->name == 'M & E Checklist')
			$checklist_id = 69519;
		else if($checklist->name == 'HTC Lab Register (MOH 362)')
			$checklist_id = 69514;
		else if($checklist->name == 'SPI-RT Checklist')
			$checklist_id = 69683;
		return $this->onadata($checklist_id, $checklist->id);
	}
	/**
    * CURL funtion to login and process the data to be imported
    *
    * @param 
    */
    public function onadata($id, $checklist)
    {
        /* Run curl request */
        //  Initiate curl
        $ch = curl_init('https://ona.io/api/v1/data/'.$id);
        //  Set all applicable options
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        //  Authentication by authorization token
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Token 9cbbf702f821cf84f3875da4aef80915f3bae6ce'));
        //  Execute curl request
        $result=curl_exec($ch);
        //  Throw any errors of any
        if(curl_error($ch))
            echo curl_error($ch);
        //  Close curl operation
        curl_close($ch);
        //  Get the data returned
        $checklist_data = json_decode($result, true);
        //  Process the data appropriately
        if($id == 69514)
        {
        	$this->htc($checklist_data);
        }
        else
        {
        	$this->process($checklist_data, $checklist);
        }
    }
     /**
     * Function for processing the requests we receive from the external system
     * and putting the data into our system.
     *
     * @var array lab_requests
     */
    public function process($data, $checklist)
    {
        //  Cleanup the data first to do away with unwanted variables
        //  Save the data to the database tables
        //	$this->htc($data);
        //	dd($data);
        foreach ($data as $key => $value)
        {
        	//dd($value);
        	$checklist_id = $checklist;
        	$facility_id = NULL;
        	$facility = $value["mysites"];
        	if(str_replace('_', ' ', $facility) === 'makandara health center')
				$facility_id = Facility::idByName('Makadara Health Center');
			else
				$facility_id = Facility::idByName(str_replace('_', ' ', $facility));
        	$qa_officer = $value['nameoftheauditor'];
        	$comment = null;
        	if(isset($value['addtionalcomments']))
        		$comment = $value['addtionalcomments'];
        	$date_started = $value['start'];
        	$date_ended = $value['end'];
        	$date_submitted = $value['_submission_time'];
        	$latitude = null;
        	$longitude = null;
        	$gps = $value["_geolocation"];
        	if(count($gps)>0)
        	{
	        	$longitude = $gps[1];
	        	$latitude = $gps[0];
	        }
        	$sdp_data = null;
        	if(array_key_exists("sdpoint", $value))
        	{
        		$sdp_data = $value["sdpoint"];
        	}
        	$start_date = Carbon::parse($value['start'])->toDateString();
        	$end_date = Carbon::parse($value['end'])->toDateString();
        	$submit_date = Carbon::parse($value['_submission_time'])->toDateString();
        	//	Save survey at this point after checking for existence
        	if($srvy = Survey::where('checklist_id', $checklist_id)->where('facility_id', $facility_id)->where('qa_officer', $qa_officer)->where('date_started', 'like', $start_date.'%')->where('date_ended', 'like', $end_date.'%')->where('date_submitted', 'like', $submit_date.'%')->first())
        	{
        		$survey = Survey::find($srvy->id);
        	}
        	else
        	{
        		$survey = new Survey;        	
        		$survey->checklist_id = $checklist_id;
        		$survey->facility_id = $facility_id;
        		$survey->qa_officer = $qa_officer;
        		$survey->comment = $comment;
        		$survey->date_started = $date_started;
        		$survey->date_ended = $date_ended;
        		$survey->date_submitted = $date_submitted;
        		$survey->longitude = $longitude;
        		$survey->latitude = $latitude;
				$survey->save();
        	}
			//	Proceed to save the rest of the data.
			//	dd($sdp_data);
			if($sdp_data)
			{
				foreach ($sdp_data as $harvey => $specter) 
	        	{
	        		//	Save survey-sdp
	    			$sdp_id = NULL;
	    			$sdp = '';
	    			if(array_key_exists("sdpoint/hh_testing_site", $specter))
						$sdp = $specter["sdpoint/hh_testing_site"];
					if(strlen($sdp)>0)
					{
						if($sdp === 'other1')
							$sdp_id = Sdp::idByName('Others');
						else if($sdp === 'artclinic')
							$sdp_id = Sdp::idByName('ART Clinic');
						else
							$sdp_id = Sdp::idById($sdp);
					}
					$comment = NULL;
					if(array_key_exists("sdpoint/opd", $specter))
						$comment = $specter["sdpoint/opd"];
					if(array_key_exists("sdpoint/pmtct", $specter))
						$comment = $specter["sdpoint/pmtct"];
					if(array_key_exists("sdpoint/othersdp", $specter))
						$comment = $specter["sdpoint/othersdp"];
					if(array_key_exists("sdpoint/other_specify", $specter))
						$comment = $specter["sdpoint/other_specify"];
					if(array_key_exists("sdpoint/ipd", $specter))
						$comment = $specter["sdpoint/ipd"];
					if(array_key_exists("sdpoint/otheripd", $specter))
						$comment = $specter["sdpoint/otheripd"];
					if(array_key_exists("sdpoint/otheropd", $specter))
						$comment = $specter["sdpoint/otheropd"];
					if(array_key_exists("sdpoint/pmctc1", $specter))
						$comment = $specter["sdpoint/pmctc1"];
					if(array_key_exists("sdpoint/otherpmtct1", $specter))
						$comment = $specter["sdpoint/otherpmtct1"];
					//	Get comments array
					if($sdp_id)
					{
						if($ss = SurveySdp::where('survey_id', $survey->id)->where('sdp_id', $sdp_id)->where('comment', $comment)->first())
						{
							$surveySdp = SurveySdp::find($ss->id);
						}
						else
						{
							$surveySdp = new SurveySdp;
							$surveySdp->survey_id = $survey->id;
							$surveySdp->sdp_id = $sdp_id;
							$surveySdp->comment = $comment;
							$surveySdp->save();
						}
						//	Save comments if any - SPI-RT
						$spirtComments = null;
						if(array_key_exists("sdpoint/Section/Section9/repeat", $specter))
							$spirtComments = $specter["sdpoint/Section/Section9/repeat"];
						if($spirtComments)
						{
							foreach ($spirtComments as $key => $value)
							{
								if(array_key_exists("sdpoint/Section/Section9/repeat/sectionno", $value) && array_key_exists("sdpoint/Section/Section9/repeat/comments1", $value))
								{
									$section = $value["sdpoint/Section/Section9/repeat/sectionno"];
									$comments = $value["sdpoint/Section/Section9/repeat/comments1"];
									if(count(DB::table('survey_spirt_comments')->where('survey_sdp_id', $surveySdp->id)->where('section_id', $section)->get()) == 0)
									{
										DB::table('survey_spirt_comments')->insert(['survey_sdp_id' => $surveySdp->id, 'section_id' => $section, 'comments' => $comments]);
									}
								}
							}
						}

						//	Save the rest of the data
						foreach ($specter as $mike => $ross)
						{
							$id = substr($mike, strrpos($mike, '/')+1);
							if(($id === 'yesthen') || 
								($id === 'youdone') || 
								($id === 'newpage') || 
								($id === 'hh_testing_site') || 
								($id === 'sec1percentage') || 
								($id === 'sec2percentage') || 
								($id === 'sec3percentage') || 
								($id === 'sec4percentage') || 
								($id === 'sec5percentage') || 
								($id === 'sec6percentage') || 
								($id === 'sec7percentage') || 
								($id === 'sec8percentage') || 
								($id === 'sec81percentage') ||
								($id === 'sec9percentage') ||   
								($id === 'sec91percentage') ||  
								($id === 'sec1calc') || 
								($id === 'sec2calc') || 
								($id === 'sec3calc') || 
								($id === 'sec4calc') || 
								($id === 'sec5calc') || 
								($id === 'sec6calc') || 
								($id === 'sec7calc') || 
								($id === 'sec8calc') || 
								($id === 'sec81calc') || 
								($id === 'sec9calc') ||
								($id === 'sec91calc') || 
								($id === 'opd') || 
								($id === 'pmtct') || 
								($id === 'othersdp') || 
								($id === 'other_specify') || 
								($id === 'ipd') || 
								($id === 'otheripd') || 
								($id === 'otheropd') || 
								($id === 'pmctc1') || 
								($id === 'otherpmtct1') || 
								($id === 'repeat'))
							{
								continue;
							}
							else
							{
								$question_id = Question::idById($id);
								if($sq = SurveyQuestion::where('survey_sdp_id', $surveySdp->id)->where('question_id', $question_id)->first())
								{
									$surveyQstn = SurveyQuestion::find($sq->id);
								}
								else
								{
									$surveyQstn = new SurveyQuestion;
									$surveyQstn->survey_sdp_id = $surveySdp->id;
									$surveyQstn->question_id = $question_id;
									$surveyQstn->save();
								}
								if(count($surveyQstn->sd) == 0)
								{
									$surveyData = new SurveyData;
									$surveyData->survey_question_id = $surveyQstn->id;
									$surveyData->answer = $ross;
									$surveyData->save();
								}
							}
						}
					}
	        	}
	        }
			//	End here
		}
    }

    /**
    * Function for saving the data to externalDump table
    * 
    * @param $labrequest the labrequest in array format
    * @param $testId the testID to save with the labRequest or 0 if we do not have the test
    *        in our systems.
    */
    public function htc($value)
    {
    	//  Get all the data.
        //	dd($checklistData);
        foreach ($checklistData as $key => $value) 
        {
        	//	dd($value);
        	$checklist_id = Checklist::idByName('HTC Lab Register (MOH 362)');
        	$facility_id = NULL;
        	$facility = $value["mysites"];
        	if(str_replace('_', ' ', $facility) === 'makandara health center')
				$facility_id = Facility::idByName('Makadara Health Center');
			else
				$facility_id = Facility::idByName(str_replace('_', ' ', $facility));
        	$qa_officer = $value['nameoftheauditor'];
        	$comment = null;
        	if(isset($value['addtionalcomments']))
        		$comment = $value['addtionalcomments'];
        	$date_started = $value['start'];
        	$date_ended = $value['end'];
        	$date_submitted = $value['_submission_time'];
        	$latitude = null;
        	$longitude = null;
        	$gps = $value["_geolocation"];
        	if(count($gps)>0)
        	{
	        	$longitude = $gps[1];
	        	$latitude = $gps[0];
	        }
        	$sdp_data = null;
        	if(array_key_exists("sdprepeat", $value))
        	{
        		$sdp_data = $value["sdprepeat"];
        	}
        	$start_date = Carbon::parse($value['start'])->toDateString();
        	$end_date = Carbon::parse($value['end'])->toDateString();
        	$submit_date = Carbon::parse($value['_submission_time'])->toDateString();
        	//	Save survey at this point after checking for existence
        	if($srvy = Survey::where('checklist_id', $checklist_id)->where('facility_id', $facility_id)->where('qa_officer', $qa_officer)->where('date_started', 'like', $start_date.'%')->where('date_ended', 'like', $end_date.'%')->where('date_submitted', 'like', $submit_date.'%')->first())
        	{
        		$survey = Survey::find($srvy->id);
        	}
        	else
        	{
        		$survey = new Survey;        	
        		$survey->checklist_id = $checklist_id;
        		$survey->facility_id = $facility_id;
        		$survey->qa_officer = $qa_officer;
        		$survey->comment = $comment;
        		$survey->date_started = $date_started;
        		$survey->date_ended = $date_ended;
        		$survey->date_submitted = $date_submitted;
        		$survey->longitude = $longitude;
        		$survey->latitude = $latitude;
				$survey->save();
        	}
        	if($sdp_data)
        	{
	        	foreach ($sdp_data as $harvey => $specter) 
	        	{
	        		$sdp_id = NULL;
					$sdp = '';
	    			if(array_key_exists("sdprepeat/hh_testing_site", $specter))
						$sdp = $specter["sdprepeat/hh_testing_site"];
					if(strlen($sdp)>0)
					{
						if($sdp === 'other1')
							$sdp_id = Sdp::idByName('Others');
						else if($sdp === 'artclinic')
							$sdp_id = Sdp::idByName('ART Clinic');
						else
							$sdp_id = Sdp::idById($sdp);
					}

					$comment = NULL;
					if(array_key_exists("sdprepeat/opd", $specter))
						$comment = $specter["sdprepeat/opd"];
					if(array_key_exists("sdprepeat/pmtct", $specter))
						$comment = $specter["sdprepeat/pmtct"];
					if(array_key_exists("sdprepeat/othersdp", $specter))
						$comment = $specter["sdprepeat/othersdp"];
					if(array_key_exists("sdprepeat/other_specify", $specter))
						$comment = $specter["sdprepeat/other_specify"];
					if(array_key_exists("sdprepeat/ipd", $specter))
						$comment = $specter["sdprepeat/ipd"];
					if(array_key_exists("sdprepeat/otheripd", $specter))
						$comment = $specter["sdprepeat/otheripd"];
					if(array_key_exists("sdprepeat/otheropd", $specter))
						$comment = $specter["sdprepeat/otheropd"];
					if(array_key_exists("sdprepeat/pmctc1", $specter))
						$comment = $specter["sdprepeat/pmctc1"];
					if(array_key_exists("sdprepeat/otherpmtct1", $specter))
						$comment = $specter["sdprepeat/otherpmtct1"];
					$pages = null;
					if(array_key_exists("sdprepeat/pagerepeat", $specter))
					{
						$pages = $specter["sdprepeat/pagerepeat"];
					}
					if($sdp_id)
					{
						if($ss = SurveySdp::where('survey_id', $survey->id)->where('sdp_id', $sdp_id)->where('comment', $comment)->first())
						{
							$surveySdp = SurveySdp::find($ss->id);
						}
						else
						{
							$surveySdp = new SurveySdp;
							$surveySdp->survey_id = $survey->id;
							$surveySdp->sdp_id = $sdp_id;
							$surveySdp->comment = $comment;
							$surveySdp->save();
						}
						if($pages)
						{
							$page = 0;
							foreach ($pages as $louis => $litt) 
							{
								$page++;
								if($ssdPage = HtcSurveyPage::where('survey_sdp_id', $surveySdp->id)->where('page', $page)->first())
								{
									$surveyPage = HtcSurveyPage::find($ssdPage->id);
								}
								else
								{

									$surveyPage = new HtcSurveyPage;
									$surveyPage->survey_sdp_id = $surveySdp->id;
									$surveyPage->page = $page;
									$surveyPage->save();
								}
								foreach ($litt as $ned => $stark) 
								{
									$id = substr($ned, strrpos($ned, '/')+1);
									if(($id === 'youdone') || ($id === 'newpage'))
									{
										continue;
									}
									else
									{
										
										$question_id = Question::idById($id);
										if($qstn = HtcSurveyPageQuestion::where('htc_survey_page_id', $surveyPage->id)->where('question_id', $question_id)->first())
										{
											$surveyPageQstn = HtcSurveyPageQuestion::find($qstn->id);
										}
										else
										{
											$surveyPageQstn = new HtcSurveyPageQuestion;
											$surveyPageQstn->htc_survey_page_id = $surveyPage->id;
											$surveyPageQstn->question_id = $question_id;
											$surveyPageQstn->save();
										}

										//	htc-survey-page-data
										if(count($surveyPageQstn->data) == 0)
										{
											$pageData = new HtcSurveyPageData;
											$pageData->htc_survey_page_question_id = $surveyPageQstn->id;
											$pageData->answer = $stark;
											$pageData->save();
										}
									}
								}
							}
						}
					}
	        	}
	        }
		}
    }
	/**
	 * Return summary by county
	 * @param  int  $id
	 * @return Response
	 */
	public function county($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Get counties
		$counties = County::all();
		return view('survey.county', compact('checklist', 'counties'));
	}
	/**
	 * Return summary by sub-county
	 * @param  int  $id
	 * @return Response
	 */
	public function subcounty($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Get sub-counties
		$subCounties = SubCounty::all();
		$county = null;
		$subCounty = null;
		if(Auth::user()->hasRole('County Lab Coordinator'))
			$county = County::find(Auth::user()->tier->tier);
		if(Auth::user()->hasRole('Sub-County Lab Coordinator'))
			$subCounty = SubCounty::find(Auth::user()->tier->tier);
		if($county)
		{
			$subCounties = $county->subCounties;
		}
		return view('survey.subcounty', compact('checklist', 'subCounties'));
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showSdp($id)
	{
		//	Get survey
		$surveysdp = SurveySdp::find($id);
		return view('survey.surveysdp', compact('surveysdp'));
	}
	/**
	 * Display the specified resource to be updated.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function editSdp($id)
	{
		//	Get survey
		$surveysdp = SurveySdp::find($id);
		//	Get cadres
		$cadres = Cadre::all();
		//	Get sdps
		$sdps = Sdp::lists('name', 'id');
		//	Get audit_types
		$audit_types = AuditType::lists('name', 'name');
		//	Get test_kits
		$test_kits = TestKit::lists('name', 'name');
		//	Get algorithms
		$algorithms = Algorithm::lists('name', 'name');
		//	Get affiliations
		$affiliations = Affiliation::lists('name', 'name');
		return view('survey.editsurveysdp', compact('surveysdp', 'cadres', 'sdps', 'audit_types', 'test_kits', 'algorithms', 'affiliations'));
	}	
	/**
	 * Display the specified resource to be updated.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateSdp($id)
	{
		//	Get survey
		$surveySdp = SurveySdp::find($id);
		$surveySdp->sdp_id = Input::get('sdp');
		$surveySdp->save();
		//	Proceed to survey-questions
		foreach (Input::all() as $key => $value) 
		{
			if((stripos($key, 'token') !==FALSE) || (stripos($key, 'method') !==FALSE))
				continue;
			else if((stripos($key, 'text') !==FALSE) || (stripos($key, 'radio') !==FALSE) || (stripos($key, 'field') !==FALSE) || (stripos($key, 'textarea') !==FALSE) || (stripos($key, 'checkbox') !==FALSE) || (stripos($key, 'select') !==FALSE)){
				$questionId = $this->strip($key);
				if(is_array($value))
					$value = implode(', ', $value);
				$sq = $surveySdp->sq((int)$questionId);
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
		return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-updated', 1));
	}
	/**
	 * Display the specified resource to be updated.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function editPage($id)
	{
		//	Get page
		$page = HtcSurveyPage::find($id);
		//	Get survey sdp
		$ssdp = SurveySdp::find($page->survey_sdp_id);
		//	Get cadres
		$cadres = Cadre::all();
		//	Get sdps
		$sdps = Sdp::lists('name', 'id');
		//	Get audit_types
		$audit_types = AuditType::lists('name', 'name');
		//	Get test_kits
		$test_kits = TestKit::lists('name', 'name');
		//	Get algorithms
		$algorithms = Algorithm::lists('name', 'name');
		//	Get affiliations
		$affiliations = Affiliation::lists('name', 'name');
		return view('survey.editpage', compact('page', 'ssdp', 'cadres', 'sdps', 'audit_types', 'test_kits', 'algorithms', 'affiliations'));
	}	
	/**
	 * Display the specified resource to be updated.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updatePage($id)
	{
		//	Get page
		$page = HtcSurveyPage::find($id);
		//	Proceed to survey-questions
		foreach (Input::all() as $key => $value) 
		{
			if((stripos($key, 'token') !==FALSE) || (stripos($key, 'method') !==FALSE))
				continue;
			else if((stripos($key, 'text') !==FALSE) || (stripos($key, 'radio') !==FALSE) || (stripos($key, 'field') !==FALSE) || (stripos($key, 'textarea') !==FALSE) || (stripos($key, 'checkbox') !==FALSE) || (stripos($key, 'select') !==FALSE)){
				$questionId = $this->strip($key);
				if(is_array($value))
					$value = implode(', ', $value);
				$sq = $page->sq((int)$questionId);
				if($sq)
				{
					$sd = $sq->data;
					$sd->answer = $value;
					$sd->save();
				}
				else
				{
					//	Create the htc-survey-question
					$sq = new HtcSurveyPageQuestion;
					$sq->htc_survey_page_id = $page->id;
					$sq->question_id = $questionId;
					$sq->save();
					//	htc-survey-data
					$sd = new HtcSurveyPageData;
					$sd->htc_survey_page_question_id = $sq->id;
					$sd->answer = $value;
					$sd->save();
				}
			}
		}
		//	Redirect
		$url = session('SOURCE_URL');
		return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-updated', 1));
	}
	/**
	 * Duplicate survey-sdp
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function duplicate($id = 0)
	{
		//	Get survey-sdp-is
		if($id == 0)
		{
			$id = Input::get('ssdpForDuplicate');
		}
		//	Get sdp to duplicate data
		$ssdp = Input::get('sdp');
		//	Begin duplication
		$ssdpToDuplicate = SurveySdp::find($id);
		//	Check if it already exists
		if($surveySdp = SurveySdp::where('survey_id', $ssdpToDuplicate->survey_id)->where('sdp_id', $ssdp)->where('comment', $ssdpToDuplicate->comment)->where('created_at', $ssdpToDuplicate->created_at)->where('updated_at', $ssdpToDuplicate->updated_at)->first())
		{
			//	Redirect
			$url = session('SOURCE_URL');
			return redirect()->to($url)->with('warning', Lang::choice('messages.record-already-exists', 1));
		}
		else
		{
			$ssdpDuplicate = new SurveySdp();
			$ssdpDuplicate->survey_id = $ssdpToDuplicate->survey_id;
			$ssdpDuplicate->sdp_id = $ssdp;
			$ssdpDuplicate->comment = $ssdpToDuplicate->comment;
			$ssdpDuplicate->created_at = $ssdpToDuplicate->created_at;
			$ssdpDuplicate->updated_at = $ssdpToDuplicate->updated_at;
			$ssdpDuplicate->save();
			//	Proceed to check whether spirt/m&e or htc register
			if($ssdpToDuplicate->survey->checklist->name == 'HTC Lab Register (MOH 362)')
			{
				//	Get pages
				$pages = $ssdpToDuplicate->pages;
				foreach ($pages as $page)
				{
					$pageDuplicate = new HtcSurveyPage;
					$pageDuplicate->survey_sdp_id = $ssdpDuplicate->id;
					$pageDuplicate->page = $page->page;
					$pageDuplicate->created_at = $page->created_at;
					$pageDuplicate->updated_at = $page->updated_at;
					$pageDuplicate->save();
					//	Get page questions
					foreach ($page->questions as $question)
					{
						//	Save questions first
						$questionDuplicate = new HtcSurveyPageQuestion;
						$questionDuplicate->htc_survey_page_id = $pageDuplicate->id;
						$questionDuplicate->question_id = $question->question_id;
						$questionDuplicate->created_at = $question->created_at;
						$questionDuplicate->updated_at = $question->updated_at;
						$questionDuplicate->save();
						//	Save data
						if($question->data)
						{
							$dataDuplicate = new HtcSurveyPageData;
							$dataDuplicate->htc_survey_page_question_id = $questionDuplicate->id;
							$dataDuplicate->answer = $question->data->answer;
							$dataDuplicate->comment = $question->data->comment;
							$dataDuplicate->created_at = $question->data->created_at;
							$dataDuplicate->updated_at = $question->data->updated_at;
							$dataDuplicate->save();
						}
					}
				}
			}
			else
			{
				//	Get survey-sdp-questions
				$pages = $ssdpToDuplicate->pages;
				foreach ($ssdpToDuplicate->sqs as $question)
				{
					//	Save questions first
					$questionDuplicate = new SurveyQuestion;
					$questionDuplicate->survey_sdp_id = $ssdpDuplicate->id;
					$questionDuplicate->question_id = $question->question_id;
					$questionDuplicate->created_at = $question->created_at;
					$questionDuplicate->updated_at = $question->updated_at;
					$questionDuplicate->save();
					//	Save data
					if($question->sd)
					{
						$dataDuplicate = new SurveyData;
						$dataDuplicate->survey_question_id = $questionDuplicate->id;
						$dataDuplicate->answer = $question->sd->answer;
						$dataDuplicate->comment = $question->sd->comment;
						$dataDuplicate->created_at = $question->sd->created_at;
						$dataDuplicate->updated_at = $question->sd->updated_at;
						$dataDuplicate->save();
					}
				}
			}
		}
		//	Redirect
		$url = session('SOURCE_URL');
		return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-duplicated', 1));
	}
	/**
	 * Remove the specified resource from storage (soft delete).
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteSdp($id)
	{
		//	soft delete the survey-sdp
		$ssdp = SurveySdp::find($id);
		$ssdpInUse = SurveyQuestion::where('survey_sdp_id', $ssdp->id)->first();
		$htcSsdpInUse = HtcSurveyPage::where('survey_sdp_id', $ssdp->id)->first();
		if($ssdpInUse || $htcSsdpInUse)
		{
			//	The survey-sdp has data
			if($ssdpInUse)
			{
				//	Delete survey-data
				foreach ($ssdp->sqs as $question)
				{
					$question->sd->delete();
					$question->delete();
				}
			}
			else
			{
				//	Delete page data
				foreach ($ssdp->pages as $page)
				{
					foreach ($page->questions as $question)
					{
						$question->data->delete();
						$question->delete();
					}
					$page->delete();
				}
			}
		}
		if($survey = Survey::find($ssdp->survey->id)->sdp->count()==1)
		{
			$ssdp->delete();
			$survey->delete();
		}
		else
		{
			$ssdp->delete();
		}
		//	Redirect
		$url = session('SOURCE_URL');
		return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-deleted', 1));
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function page($id)
	{
		//	Get page
		$page = HtcSurveyPage::find($id);
		return view('survey.page', compact('page'));
	}
	/**
	 * Remove the specified resource from storage (soft delete).
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deletePage($id)
	{
		//	soft delete the htc-survey-page
		$page = HtcSurveyPage::find($id);
		//	Delete page data
		foreach ($page->questions as $question)
		{
			$question->data->delete();
			$question->delete();
		}
		//	Check if it is the only page
		if($ssdp = SurveySdp::find($page->survey_sdp_id)->pages->count()==1)
		{
			//	Check if it is the only survey-sdp
			if($survey = Survey::find($ssdp->survey->id)->sdp->count()==1)
			{
				$page->delete();
				$ssdp->delete();
				$survey->delete();
			}
			else
			{
				$page->delete();
				$ssdp->delete();
			}		
		}
		else
		{
			$page->delete();
		}
		//	Redirect
		$url = session('SOURCE_URL');
		return redirect()->to($url)->with('message', Lang::choice('messages.record-successfully-deleted', 1));
	}
	/**
	 * Function to download collection summary
	 *
	 */
	public function collectionDownload($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Get QA Officers
		$qa = Survey::select('qa_officer')
					->where('checklist_id', $checklist->id)
					->groupBy('qa_officer')
					->get();
		Excel::create('QA Officer Collection Summary for '.$checklist->name.' - '.date('d-m-Y H:i:s'), function($excel) use($qa)
		{

		    $excel->sheet('No. of Questionnaires completed', function($sheet) use($qa)
		    {
		    	$sheet->appendRow(array(Lang::choice('messages.qa-officer', 1), Lang::choice('messages.no-of-questionnaire', 1)));
		    	foreach ($qa as $officer)
		    	{
		    		$sheet->appendRow(array($officer->qa_officer, Survey::questionnaires($officer->qa_officer)));
		    	}
		    });

		})->export('xlsx');
	}
	/**
	 * Function to download county submission summary 
	 *
	 */
	public function countyDownload($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Get counties
		$counties = County::all();
		Excel::create('County Submissions Summary for '.$checklist->name.' - '.date('d-m-Y H:i:s'), function($excel) use($counties, $checklist)
		{

		    $excel->sheet('No. of Questionnaires completed', function($sheet) use($counties, $checklist)
		    {
		    	$sheet->appendRow(array(Lang::choice('messages.county', 1), Lang::choice('messages.no-of-questionnaire', 1)));
		    	foreach ($counties as $county)
		    	{
		    		$sheet->appendRow(array($county->name, $county->submissions($checklist->id)));
		    	}
		    });

		})->export('xlsx');
	}
	/**
	 * Function to download sub-county submission summary 
	 *
	 */
	public function subcountyDownload($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Get sub-counties
		$subCounties = SubCounty::all();
		Excel::create('Sub-County Submissions Summary for '.$checklist->name.' - '.date('d-m-Y H:i:s'), function($excel) use($subCounties, $checklist)
		{

		    $excel->sheet('No. of Questionnaires completed', function($sheet) use($subCounties, $checklist)
		    {
		    	$sheet->appendRow(array(Lang::choice('messages.sub-county', 1), Lang::choice('messages.no-of-questionnaire', 1)));
		    	foreach ($subCounties as $subCounty)
		    	{
		    		$sheet->appendRow(array($subCounty->name, $subCounty->submissions($checklist->id)));
		    	}
		    });

		})->export('xlsx');
	}
	/**
	 * Function to download facility submission summary 
	 *
	 */
	public function facilityDownload($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Get facilities
		$facilities = Facility::all();
		Excel::create('Facility Submissions Summary for '.$checklist->name.' - '.date('d-m-Y H:i:s'), function($excel) use($facilities, $checklist)
		{

		    $excel->sheet('No. of Questionnaires completed', function($sheet) use($facilities, $checklist)
		    {
		    	$sheet->appendRow(array(Lang::choice('messages.facility', 1), Lang::choice('messages.no-of-questionnaire', 1)));
		    	foreach ($facilities as $facility)
		    	{
		    		$sheet->appendRow(array($facility->name, $facility->submissions($checklist->id)));
		    	}
		    });

		})->export('xlsx');
	}
	/**
	 * Function to download facility submission summary per sdp
	 *
	 */
	public function sdpDownload($id)
	{
		//	Get checklist
		$checklist = Checklist::find($id);
		//	Get facilities
		$facilities = Facility::all();
		Excel::create('Facility Submissions Summary for '.$checklist->name.' - '.date('d-m-Y H:i:s'), function($excel) use($facilities, $checklist)
		{

		    $excel->sheet('No. of Questionnaires completed', function($sheet) use($facilities, $checklist)
		    {
		    	$counter = 0;
		    	$sheet->appendRow(array(Lang::choice('messages.count', 1), Lang::choice('messages.facility', 1), Lang::choice('messages.sdp', 1), Lang::choice('messages.comment', 1)));
		    	foreach ($facilities as $facility)
		    	{
		    		foreach ($facility->surveys()->where('checklist_id', $checklist->id)->get() as $survey)
		    		{
		    			foreach ($survey->sdps as $ssdp)
		    			{	
		    				$counter++;	    				
		    				$sheet->appendRow(array($counter, $facility->name, $ssdp->sdp->name, $ssdp->comment));
		    			}
		    		}
		    	}
		    });

		})->export('xlsx');
	}
	/**
	 * Function to download survey as submitted by the QA officer
	 *
	 */
	public function surveyDownload($id)
	{
		//	Get survey-sdp
		$ssdp = SurveySdp::find($id);
		//	Get summary of the ssdp
		$summary = array(
			array(Lang::choice('messages.checklist', 1), $ssdp->survey->checklist->name),
			array(Lang::choice('messages.start-time', 1), $ssdp->survey->date_started),
			array(Lang::choice('messages.qa-officer', 1), $ssdp->survey->qa_officer),
			array(Lang::choice('messages.county', 1), $ssdp->survey->facility->subCounty->county->name),
			array(Lang::choice('messages.sub-county', 1), $ssdp->survey->facility->subCounty->name),
			array(Lang::choice('messages.facility', 1), $ssdp->survey->facility->name),
			array(Lang::choice('messages.sdp', 1), $ssdp->sdp->name),
			array(Lang::choice('messages.gps', 1), $ssdp->survey->latitude.' '.$ssdp->survey->longitude),
			array(Lang::choice('messages.comment', 1), $ssdp->survey->comment),
			array(Lang::choice('messages.end-time', 1), $ssdp->survey->date_ended),
			array(Lang::choice('messages.submit-time', 1), $ssdp->survey->date_submitted),
			array('', '')
		);
		Excel::create('Data submitted for SDP for '.$ssdp->survey->checklist->name.' - '.date('d-m-Y H:i:s'), function($excel) use($ssdp, $summary)
		{

		    $excel->sheet($ssdp->survey->facility->name.' for '.$ssdp->sdp->name, function($sheet) use($ssdp, $summary)
		    {
		    	$sheet->appendRow(array(Lang::choice('messages.question', 1), Lang::choice('messages.response', 1)));
		    	foreach ($summary as $data)
		    	{
		    		$sheet->appendRow($data);
		    	}
		    	foreach ($ssdp->sqs as $sq)
    			{	
    				$sheet->appendRow(array(Question::find($sq->question_id)->name, $sq->sd->answer));
    			}
		    });

		})->export('xlsx');
	}
	/**
	 * Function to download page data for HTC as submitted by the QA officer
	 *
	 */
	public function pageDownload($id)
	{
		//	Get page
		$page = HtcSurveyPage::find($id);
		//	Get summary of the ssdp
		$ssdp = surveySdp::find($page->survey_sdp_id);
		$summary = array(
			array(Lang::choice('messages.checklist', 1), $ssdp->survey->checklist->name),
			array(Lang::choice('messages.start-time', 1), $ssdp->survey->date_started),
			array(Lang::choice('messages.qa-officer', 1), $ssdp->survey->qa_officer),
			array(Lang::choice('messages.county', 1), $ssdp->survey->facility->subCounty->county->name),
			array(Lang::choice('messages.sub-county', 1), $ssdp->survey->facility->subCounty->name),
			array(Lang::choice('messages.facility', 1), $ssdp->survey->facility->name),
			array(Lang::choice('messages.sdp', 1), $ssdp->sdp->name),
			array(Lang::choice('messages.gps', 1), $ssdp->survey->latitude.' '.$ssdp->survey->longitude),
			array(Lang::choice('messages.comment', 1), $ssdp->survey->comment),
			array(Lang::choice('messages.end-time', 1), $ssdp->survey->date_ended),
			array(Lang::choice('messages.submit-time', 1), $ssdp->survey->date_submitted),
			array('', '')
		);
		Excel::create('Data submitted for SDP for '.$ssdp->survey->checklist->name.' - '.date('d-m-Y H:i:s'), function($excel) use($page, $ssdp, $summary)
		{

		    $excel->sheet($ssdp->sdp->name, function($sheet) use($page, $summary)
		    {
		    	$sheet->appendRow(array(Lang::choice('messages.question', 1), Lang::choice('messages.response', 1)));
		    	foreach ($summary as $data)
		    	{
		    		$sheet->appendRow($data);
		    	}
		    	foreach ($page->questions as $question)
    			{	
    				$qstn = Question::find($question->question_id);
    				$sheet->appendRow(array($qstn->name, $question->data->answer));
    			}
		    });

		})->export('xlsx');
	}
	/**
	 * Function to get overview of submissions
	 *
	 */
	public function overview()
	{	
		$htc_me = 0;
       	$htc_spirt = 0;
       	$spirt_me = 0;
       	$htc = Checklist::idByName('HTC Lab Register (MOH 362)');
       	$me = Checklist::idByName('M & E Checklist');
       	$spi = Checklist::idByName('SPI-RT Checklist');
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
       	return view('survey.overview', compact('checklists', 'htc_me', 'htc_spirt', 'spirt_me', 'complete', 'all', 'pmtcts', 'pmtctMeSpi'));
	}
}
$excel = App::make('excel');
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

use Illuminate\Http\Request;
use Response;
use Auth;
use Input;
use Lang;
use App;
use Excel;

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
		
		return view('survey.list', compact('checklist','checklist_id'));
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
		return view('survey.show', compact('survey', 'checklist_id'));
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
					->where('checklist_id', $checklist->id)
					->groupBy('qa_officer')
					->get();
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
	public function api($id)
	{
		//	Get specific checklist
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
        //dd($data);
        foreach ($data as $key => $value)
        {
        	$checklist_id = $checklist;
        	$facility_id = NULL;
        	$qa_officer = NULL;
        	$comment = NULL;
        	$date_started = NULL;
        	$date_ended = NULL;
        	$date_submitted = NULL;
        	$longitude = NULL;
        	$latitude = NULL;
        	foreach ($value as $harvey => $specter) 
        	{
        		if(strpos($harvey, 'mysites') !== false)
        		{
					$facility_id = Facility::idByName(str_replace('_', ' ', $specter));
				}
				if(strpos($harvey, 'nameoftheauditor') !== false)
				{
					$qa_officer = $specter;
				}
				if(strpos($harvey, 'addtionalcomments') !== false)
				{
					$comment = $specter;
				}
				if(strpos($harvey, 'start') !== false)
				{
					$date_started = $specter;
				}
				if(strpos($harvey, 'end') !== false)
				{
					$date_ended = $specter;
				}
				if(strpos($harvey, '_submission_time') !== false)
				{
					$date_submitted = $specter;
				}
				if(strpos($harvey, '_geolocation') !== false)
				{
					$longitude = $specter[0];
					$latitude = $specter[1];
				}				
        	}
        	//	Save survey at this point after checking for existence
        	if($srvy = Survey::where('checklist_id', $checklist)->where('facility_id', $facility_id)->where('qa_officer', $qa_officer)->where('date_started', $date_started)->where('date_ended', $date_ended)->where('date_submitted', $date_submitted)->first())
        	{
        		$survey = Survey::find($srvy)->id;
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
			foreach ($value as $harvey => $specter)
			{
				//dd($specter);
				if(strpos($harvey, 'sdpoint') !== false)
				{
					foreach ($specter as $mike => $ross)
					{
						//	Create survey-sdp
						$surveySdp = new SurveySdp;
						$surveySdp->survey_id = $survey->id;
						$sdp_id = NULL;
						$comment = NULL;
						foreach ($ross as $louis => $litt)
						{
							//	Get sdp id
							if(strpos($louis, 'hh_testing_site') !== false)
							{
								if($litt === 'other1')
									$sdp_id = Sdp::idByName('Others');
								else if($litt === 'artclinic')
									$sdp_id = Sdp::idByName('ART Clinic');
								else
									$sdp_id = Sdp::idById($litt);
							}
							if((strpos($louis, 'opd1') !== false) || (strpos($louis, 'pmtct1') !== false) || (strpos($louis, 'othersdp') !== false))
							{
								$comment = $litt;
							}
						}
						if(!isset($sdp_id))
						{
							continue;
						}
						else
						{
							$surveySdp->sdp_id = $sdp_id;
							$surveySdp->comment = $comment;

							if($ss = SurveySdp::where('survey_id', $survey->id)->where('sdp_id', $sdp_id)->first())
								$surveySdp = SurveySdp::find($ss->id);
							else
								$surveySdp->save();
							//	Get questions from database
							$questions = array();
							foreach (Checklist::find($checklist)->sections as $section) 
							{
								foreach ($section->questions as $question) 
								{
									if($question->identifier)
									{
										array_push($questions, $question->identifier);
									}
								}
							}
							//	Save specific me/spi-rt info
							if($checklist == Checklist::idByName('M & E Checklist'))
							{
								$me_info = new MeInfo;
								$me_info->survey_sdp_id = $surveySdp->id;
								//	variables to be used
								$audit_type_id = null;
								$algorithm_id = null;
								$screening = 4;
								$confirmatory = 4;
								$tie_breaker = 4;
								foreach ($ross as $louis => $litt)
								{
									//	Get baseline id
									if(strpos($louis, 'audittype') !== false)
									{
										$audit_type_id = AuditType::idByName($litt);
									}
									//	Get algorithm id
									if(strpos($louis, 'algorithm') !== false)
									{
										$algorithm_id = Algorithm::idByName($litt);
									}
									//	Get screening id
									if(strpos($louis, 'screen') !== false)
									{
										$screening = TestKit::idByName($litt);
									}
									//	Get confirmatory id
									if(strpos($louis, 'contirmatory') !== false)
									{
										$confirmatory = TestKit::idByName($litt);
									}
									//	Get tie-breaker id
									if(strpos($louis, 'tiebreaker') !== false)
									{
										$tie_breaker = TestKit::idByName($litt);
									}
								}
								$me_info->audit_type_id = $audit_type_id;
								$me_info->algorithm_id = $algorithm_id;
								$me_info->screening = $screening;
								$me_info->confirmatory = $confirmatory;
								$me_info->tie_breaker = $tie_breaker;
								//	Save survey-me-info
								if(($audit_type_id!=NULL) && ($algorithm_id!=NULL) && ($screening!=NULL) && ($confirmatory!=NULL) && ($tie_breaker!=NULL))
								{
									$me_info->save();
									//dd($me_info);
									foreach ($ross as $louis => $litt)
									{
										$surveyQstn = new SurveyQuestion;
										$surveyQstn->survey_sdp_id = $surveySdp->id;
										$question_id = NULL;
										if((strpos($louis, 'youdone') !== false) || strpos($louis, 'newpage') !== false)
										{
											continue;
										}
										else
										{
											foreach ($questions as $question) 
											{
												if(strpos($louis, $question) !== false)
												{
													$question_id = Question::idById($question);
												}
											}
										}
										$surveyQstn->question_id = $question_id;
										if(empty($surveyQstn->question_id))
										{
											continue;
										}
										else
										{
											if($sq = SurveyQuestion::where('survey_sdp_id', $surveySdp->id)->where('question_id', $question_id)->first())
												$surveyQstn = SurveyQuestion::find($sq->id);
											else
												$surveyQstn->save();
											//	survey-data
											$surveyData = new SurveyData;
											$surveyData->survey_question_id = $surveyQstn->id;
											Question::find($surveyQstn->question_id)->isScorable()?$surveyData->answer = Answer::nameByScore($litt):$surveyData->answer = $litt;
											$surveyData->save();
											//	survey-score
											if(Question::find($surveyQstn->question_id)->isScorable())
											{
												$ss = new SurveyScore;
												$ss->survey_question_id = $surveyQstn->id;
												$ss->score = $litt;
												$ss->save();
											}
										}
									}
								}
								else
									continue;
							}
							else
							{		
								$spirt_info = new SpirtInfo;
								$spirt_info->survey_sdp_id = $surveySdp->id;
								$affiliation_id = null;
								foreach ($ross as $louis => $litt)
								{
									//	Get affiliation id
									if(strpos($louis, 'affiliation') !== false)
									{
										if(strpos($litt, '_') !== false)
										{
											if(str_replace('_', ' ', $litt) === 'faith based organisation')
												$affiliation_id = Affiliation::idByName('Faith Based Organization');
											else if(str_replace('_', ' ', $litt) === 'non governmental organisation')
												$affiliation_id = Affiliation::idByName('Non Governmental Organization');
										}
										else
										{
											$affiliation_id = Affiliation::idByName($litt);
										}
									}
								}
								$spirt_info->affiliation_id = $affiliation_id;
								//	Save survey-spirt-info
								if($spirt_info->affiliation_id!=NULL)
								{
									$spirt_info->save();
									foreach ($ross as $louis => $litt){
										$surveyQstn = new SurveyQuestion;
										$surveyQstn->survey_sdp_id = $surveySdp->id;
										$question_id = NULL;
										if((strpos($louis, 'youdone') !== false) || strpos($louis, 'newpage') !== false)
										{
											continue;
										}
										else
										{
											foreach ($questions as $question) 
											{
												if(strpos($louis, $question) !== false)
												{
													$question_id = Question::idById($question);
												}
											}
										}
										$surveyQstn->question_id = $question_id;
										if(empty($surveyQstn->question_id))
										{
											continue;
										}
										else
										{
											if($sq = SurveyQuestion::where('survey_sdp_id', $surveySdp->id)->where('question_id', $question_id)->first())
												$surveyQstn = SurveyQuestion::find($sq->id);
											else
												$surveyQstn->save();
											//	survey-data
											$surveyData = new SurveyData;
											$surveyData->survey_question_id = $surveyQstn->id;
											Question::find($surveyQstn->question_id)->isScorable()?$surveyData->answer = Answer::nameByScore($litt):$surveyData->answer = $litt;
											$surveyData->save();
											//	survey-score
											if(Question::find($surveyQstn->question_id)->isScorable())
											{
												$ss = new SurveyScore;
												$ss->survey_question_id = $surveyQstn->id;
												$ss->score = $litt;
												$ss->save();
											}
										}
									}
								}
								else
									continue;
							}
						}
					}
				}
			}
		}
    }

    /**
    * Function for saving the data to externalDump table
    * 
    * @param $labrequest the labrequest in array format
    * @param $testId the testID to save with the labRequest or 0 if we do not have the test
    *        in our systems.
    */
    public function htc($checklistData)
    {
    	//  Get all the data.
        //	dd($checklistData);
        foreach ($checklistData as $key => $value) 
        {
        	$checklist_id = Checklist::idByName('HTC Lab Register (MOH 362)');
        	$facility_id = NULL;
        	$qa_officer = NULL;
        	$comment = NULL;
        	$date_started = NULL;
        	$date_ended = NULL;
        	$date_submitted = NULL;
        	$longitude = NULL;
        	$latitude = NULL;
        	foreach ($value as $harvey => $specter) 
        	{
        		if(strpos($harvey, 'mysites') !== false)
        		{
        			if(str_replace('_', ' ', $specter) === 'makandara health center')
        				$facility_id = Facility::idByName('Makadara Health Center');
        			else
						$facility_id = Facility::idByName(str_replace('_', ' ', $specter));
				}
				if(strpos($harvey, 'nameoftheauditor') !== false)
				{
					$qa_officer = $specter;
				}
				if(strpos($harvey, 'addtionalcomments') !== false)
				{
					$comment = $specter;
				}
				if(strpos($harvey, 'start') !== false)
				{
					$date_started = $specter;
				}
				if(strpos($harvey, 'end') !== false)
				{
					$date_ended = $specter;
				}
				if(strpos($harvey, '_submission_time') !== false)
				{
					$date_submitted = $specter;
				}
				if(strpos($harvey, '_geolocation') !== false)
				{
					$longitude = $specter[0];
					$latitude = $specter[1];
				}				
        	}
        	//	Save survey at this point after checking for existence
        	if($srvy = Survey::where('checklist_id', $checklist_id)->where('facility_id', $facility_id)->where('qa_officer', $qa_officer)->where('date_started', $date_started)->where('date_ended', $date_ended)->where('date_submitted', $date_submitted)->first())
        	{
        		$survey = Survey::find($srvy)->id;
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
			foreach ($value as $harvey => $specter) 
        	{
        		if(strpos($harvey, '_geolocation') === false && is_array($specter))
				{

					foreach ($specter as $mike => $ross) 
					{
						$surveySdp = new SurveySdp;
						$surveySdp->survey_id = $survey->id;
						$sdp_id = NULL;
						$comment = NULL;
						if(is_array($ross))
						{
							foreach ($ross as $rachel => $zane) 
							{
								if(strpos($rachel, 'hh_testing_site') !== false)
								{
									if($zane === 'other1')
										$sdp_id = Sdp::idByName('Others');
									else if($zane === 'artclinic')
										$sdp_id = Sdp::idByName('ART Clinic');
									else
										$sdp_id = Sdp::idById($zane);
								}
								if((strpos($rachel, 'opd') !== false) || (strpos($rachel, 'pmtct') !== false) || (strpos($rachel, 'othersdp') !== false))
								{
									$comment = $zane;
								}
							}
						}
						$surveySdp->sdp_id = $sdp_id;
						$surveySdp->comment = $comment;
						if($ss = SurveySdp::where('survey_id', $survey->id)->where('sdp_id', $sdp_id)->first())
							$surveySdp = SurveySdp::find($ss->id);
						else
							$surveySdp->save();
						foreach ($specter as $mike => $ross) 
						{
							if(is_array($ross))
							{
								foreach ($ross as $rachel => $zane) 
								{
									if(is_array($zane))
									{
										$page = 1;
										foreach ($zane as $louis => $litt) 
										{
											$surveyPage = new HtcSurveyPage;
											$surveyPage->survey_sdp_id = $surveySdp->id;
											$surveyPage->page = $page;
											$surveyPage->save();
											if(is_array($litt)){
												//	Get questions from database
												$questions = array();
												foreach (Checklist::find(Checklist::idByName('HTC Lab Register (MOH 362)'))->sections as $section) 
												{
													foreach ($section->questions as $question) 
													{
														if($question->identifier)
														{
															array_push($questions, $question->identifier);
														}
													}
												}
												//	End get questions
												foreach ($litt as $ned => $stark) 
												{
													$surveyPageQstn = new HtcSurveyPageQuestion;
													$surveyPageQstn->htc_survey_page_id = $surveyPage->id;
													$question_id = NULL;
													if((strpos($ned, 'youdone') !== false) || strpos($ned, 'newpage') !== false)
													{
														continue;
													}
													else
													{
														foreach ($questions as $question) 
														{
															if(strpos($ned, $question) !== false)
															{
																$question_id = Question::idById($question);
															}
														}
													}
													$surveyPageQstn->question_id = $question_id;
													if(empty($surveyPageQstn->question_id))
													{
														continue;
													}
													else
													{
														$surveyPageQstn->save();
														//	htc-survey-page-data
														$pageData = new HtcSurveyPageData;
														$pageData->htc_survey_page_question_id = $surveyPageQstn->id;
														$pageData->answer = $stark;
														$pageData->save();													
													}
												}
											}
											$page++;
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
}
$excel = App::make('excel');
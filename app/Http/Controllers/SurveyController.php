<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\User;
use App\Models\Question;
use App\Models\Survey;
use App\Models\surveyData;
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

use Illuminate\Http\Request;
use Response;
use Auth;
use Input;

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
		return view('survey.index', compact('checklists', 'users'));
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
	public function show($id, $checklist_id)
	{
		//	Get survey
		//$checklist_id= '1';
		$survey = Survey::find($id);
		
		return view('survey.show', compact('survey', 'checklist_id'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, $checklist_id)
	{
		//	Get survey
		$survey = Survey::find($id);
		$surveyquestion = $survey->questions;
		$facility=$survey->facility_id;
		$sdp=$survey->sdp_id;
		$algorithm=$survey->sdp_id;
		$affiliation=$survey->sdp_id;
		$audit_type=$survey->sdp_id;
		//	Get specific checklist
		$checklist = Checklist::find($checklist_id);
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
		return view('survey.edit', compact('survey','facilities','checklist', 'sdps','facility', 'sdp', 'algorithms', 'algorithm', 'affiliations', 'affiliation', 'auditTypes', 'audit_type'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$checklist_id = Input::get('checklist_id');
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
		}
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
		return view('survey.participant', compact('checklist'));
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
		return $this->onadata($checklist_id);
	}
	/**
    * CURL funtion to login and process the data to be imported
    *
    * @param 
    */
    public function onadata($id)
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
        $this->process($checklist_data);
    }
     /**
     * Function for processing the requests we receive from the external system
     * and putting the data into our system.
     *
     * @var array lab_requests
     */
    public function process($data)
    {
        //  Cleanup the data first to do away with unwanted variables
        //  Save the data to the database tables
        $this->hivqa($data);
    }

    /**
    * Function for saving the data to externalDump table
    * 
    * @param $labrequest the labrequest in array format
    * @param $testId the testID to save with the labRequest or 0 if we do not have the test
    *        in our systems.
    */
    public function hivqa($checklistData)
    {
        //  Get all the data.
        //	dd($checklistData);
        foreach ($checklistData as $key => $value) 
        {
        	$survey = new Survey;
        	foreach ($value as $harvey => $specter) 
        	{        		
        		$survey->checklist_id = Checklist::idByName('HTC Lab Register (MOH 362)');
        		if(strpos($harvey, 'mysites') !== false)
        		{
					$survey->facility_id = Facility::idByName(str_replace('_', ' ', $specter));
				}
				if(strpos($harvey, 'nameoftheauditor') !== false)
				{
					$survey->qa_officer = $specter;
				}
				if(strpos($harvey, 'addtionalcomments') !== false)
				{
					$survey->comment = $specter;
				}
				if(strpos($harvey, '_geolocation') !== false)
				{
					$survey->longitude = $specter[0];
					$survey->latitude = $specter[1];
				}
				//	Save survey at this point	
				/*if(is_array($specter))
				{
					foreach ($specter as $mike => $ross) 
					{
						if(is_array($ross))
						{
							foreach ($ross as $rachel => $zane) 
							{
								if(strpos($rachel, 'hh_testing_site') !== false)
								{
									//$sdp_id = Sdp::idByName($zane);
								}
								if(is_array($zane))
								{
									foreach ($zane as $louis => $litt) 
									{
										if(is_array($litt)){
											foreach ($litt as $ned => $stark) 
											{
												//var_dump("  =>  ".$stark);
											}
											//var_dump('###############################################################################');
										}
									}
								}
							}
							//var_dump('*********************************************************************');
						}
					}	
				}*/
        	}
			$survey->save();
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
									$sdp_id = Sdp::idById($zane);
								}
								if((strpos($rachel, 'opd') !== false) || (strpos($rachel, 'pmtct') !== false) || (strpos($rachel, 'othersdp') !== false))
								{
									$comment = $zane;
								}
								/*if(is_array($zane))
								{
									foreach ($zane as $louis => $litt) 
									{
										if(is_array($litt)){
											foreach ($litt as $ned => $stark) 
											{
												//var_dump("  =>  ".$stark);
											}
											//var_dump('###############################################################################');
										}
									}
								}*/
							}
							//var_dump('*********************************************************************');
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
									if(strpos($rachel, 'hh_testing_site') !== false)
									{
										$sdp_id = Sdp::idById($zane);
									}
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
												foreach ($litt as $ned => $stark) 
												{
													//	htc-survey-page-question
													/*$surveyPageQstn = new HtcSurveyPageQuestion;
													$surveyPageQstn->htc_survey_page_id = $surveyPage->id
													$surveyPageQstn->question_id = 1;
													$surveyPageQstn->save();*/
													//	htc-survey-page-data
													/*$pageData = new HtcSurveyPageData;
													$pageData->htc_survey_page_question_id = $surveyPageQstn->id;
													$pageData->answer = $stark;
													$pageData->save();*/
													//var_dump("  =>  ".$stark);
												}
												//var_dump('###############################################################################');
											}
											$page++;
										}
									}
								}
								//var_dump('*********************************************************************');
							}
						}
					}
				}
        	}
        	//var_dump('===================================================================');
		}
    }
}

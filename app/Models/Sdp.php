<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Sdp extends Model implements Revisionable {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sdps';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'description',
    ];

	/**
	 * Survey relationship
	 */
	public function surveys()
	{
		return $this->hasMany('App\Models\SurveySdp');
	}
	/**
	* Return Sdp ID given the name
	* @param $name the name of the user
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL){
			try 
			{
				$sdp = Sdp::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $sdp->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The sdp ` $name ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	* Calculation of positive percent[ (Total Number of Positive Results/Total Number of Specimens Tested)*100 ] - Aggregated
	*/
	public function positivePercent($facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0)
	{
		//	Initialize counts
		$positive = 0;
		$total = 0;		
		//	Check dates
		$theDate = "";
		if ($year > 0) {
			$theDate .= $year;
			if ($month > 0) {
				$theDate .= "-".sprintf("%02d", $month);
				if ($date > 0) {
					$theDate .= "-".sprintf("%02d", $date);
				}
			}
		}
		//	Declare questions to be used in calculation of both positive and total values
		$qstns = array('Test-1 Total Positive', 'Test-1 Total Negative', 'Test-2 Total Positive', 'Test-3 Total Positive');
		//	Get the counts
		foreach ($qstns as $qstn) {
			$question = Question::idByName($qstn);
			$values = HtcSurveyPageQuestion::where('question_id', $question)
											->join('htc_survey_pages', 'htc_survey_pages.id', '=', 'htc_survey_page_questions.htc_survey_page_id')
											->join('survey_sdps', 'survey_sdps.id', '=', 'htc_survey_pages.survey_sdp_id')
											->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
											->where('sdp_id', $this->id);
											if($county || $subCounty || $facility)
											{
												if($subCounty || $facility)
												{
													if($facility)
													{
														$values = $values->where('facility_id', $facility);
													}
													else
													{
														$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
																 		 ->where('sub_county_id', $subCounty);
													}
												}
												else
												{
													$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
																	 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
																	 ->where('county_id', $county);
												}
											}
											if (strlen($theDate)>0) {
												$values = $values->where('date_submitted', 'LIKE', $theDate."%");
											}
											$values = $values->get(array('htc_survey_page_questions.*'));
			foreach ($values as $key => $value) 
			{
				if(substr_count(Question::nameById($value->question_id), 'Test-1')>0)
					$total+=HtcSurveyPageQuestion::find($value->id)->data->answer;
				else
					$positive+=HtcSurveyPageQuestion::find($value->id)->data->answer;
			}			
		}
		return $total>0?round((int)$positive*100/(int)$total, 2):0;
	}
	/**
	* Calculation of positive agreement[ (Total Reactive Results from Test 2/Total Reactive Results from Test 1)*100 ]
	*/
	public function positiveAgreement($kit, $facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0)
	{
		//	Initialize counts
		$testOne = 0;
		$testTwo = 0;		
		//	Check dates
		$theDate = "";
		if ($year > 0) {
			$theDate .= $year;
			if ($month > 0) {
				$theDate .= "-".sprintf("%02d", $month);
				if ($date > 0) {
					$theDate .= "-".sprintf("%02d", $date);
				}
			}
		}
		$screen = Question::idById('screen');	//	Question whose response is either determine or khb
		//	Get pages whose screening test is as given(khb/determine)
		$pages = HtcSurveyPage::join('htc_survey_page_questions', 'htc_survey_pages.id', '=', 'htc_survey_page_questions.htc_survey_page_id')
							  ->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')
							  ->join('survey_sdps', 'survey_sdps.id', '=', 'htc_survey_pages.survey_sdp_id')
							  ->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
							  ->where('sdp_id', $this->id);
							  if($county || $subCounty || $facility)
							  {
								if($subCounty || $facility)
								{
									if($facility)
									{
										$pages = $pages->where('facility_id', $facility);
									}
									else
									{
										$pages = $pages->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
												 		 ->where('sub_county_id', $subCounty);
									}
								}
								else
								{
									$pages = $pages->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
													 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
													 ->where('county_id', $county);
								}
							  }
							  if (strlen($theDate)>0) {
								$pages = $pages->where('date_submitted', 'LIKE', $theDate."%");
							  }
							  $pages = $pages->where('question_id', $screen)
											  ->where('answer', $kit)
											  ->get(array('htc_survey_pages.*'));
		//	Declare questions to be used in calculation of both values
		$posOne = Question::idByName('Test-1 Total Positive');
		$posTwo = Question::idByName('Test-2 Total Positive');
		//	For each of the pages, get to data and add the given values
		foreach ($pages as $page)
		{
			$testOne+=$page->questions()->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')->where('question_id', $posOne)->sum('answer');
			$testTwo+=$page->questions()->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')->where('question_id', $posTwo)->sum('answer');
		}
		return $testOne>0?round((int)$testTwo*100/(int)$testOne, 2):0.00;
	}
	/**
	* Calculation of overall agreement[ ((Total Tested - Total # of Invalids on Test 1 and Test 2) – (ABS[Reactives from Test 2 –Reactives from Test 1] +ABS [ Non-reactive from Test 2- Non-reactive  from Test 1)/Total Tested – Total Number of Invalids)*100 ]
	*/
	public function overallAgreement($kit, $facility = NULL, $subCounty = NULL, $county = NULL, $year = 0, $month = 0, $date = 0)
	{
		//	Initialize variables
		$total = 0;
		$invalid = 0;
		$reactiveOne = 0;
		$nonReactiveOne = 0;
		$reactiveTwo = 0;
		$nonReactiveTwo = 0;		
		//	Check dates
		$theDate = "";
		if ($year > 0) {
			$theDate .= $year;
			if ($month > 0) {
				$theDate .= "-".sprintf("%02d", $month);
				if ($date > 0) {
					$theDate .= "-".sprintf("%02d", $date);
				}
			}
		}
		$screen = Question::idById('screen');	//	Question whose response is either determine or khb
		//	Get pages whose screening test is as given(khb/determine)
		$pages = HtcSurveyPage::join('htc_survey_page_questions', 'htc_survey_pages.id', '=', 'htc_survey_page_questions.htc_survey_page_id')
							  ->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')
							  ->join('survey_sdps', 'survey_sdps.id', '=', 'htc_survey_pages.survey_sdp_id')
							  ->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
							  ->where('sdp_id', $this->id);
							  if($county || $subCounty || $facility)
							  {
								if($subCounty || $facility)
								{
									if($facility)
									{
										$pages = $pages->where('facility_id', $facility);
									}
									else
									{
										$pages = $pages->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
												 		 ->where('sub_county_id', $subCounty);
									}
								}
								else
								{
									$pages = $pages->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
													 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
													 ->where('county_id', $county);
								}
							  }
							  if (strlen($theDate)>0) {
								$pages = $pages->where('date_submitted', 'LIKE', $theDate."%");
							  }
							  $pages = $pages->where('question_id', $screen)
											  ->where('answer', $kit)
											  ->get(array('htc_survey_pages.*'));
		//	Get questions to be used in the math
		$testOnePos = Question::idByName('Test-1 Total Positive');
		$testOneNeg = Question::idByName('Test-1 Total Negative');
		$testOneInv = Question::idByName('Test-1 Total Invalid');
		$testTwoPos = Question::idByName('Test-2 Total Positive');
		$testTwoNeg = Question::idByName('Test-2 Total Negative');
		$testTwoInv = Question::idByName('Test-2 Total Invalid');
		$totalTestOne = [$testOnePos, $testOneNeg, $testOneInv];
		$invalids = [$testOneInv, $testTwoInv];
		//	Math
		foreach ($pages as $page)
		{
			//	$clonedValue = clone $values;
			$total+=$page->questions()->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')->whereIn('question_id', $totalTestOne)->sum('answer');
			//	$clonedValue = clone $values;
			$invalid+=$page->questions()->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')->whereIn('question_id', $invalids)->sum('answer');
			//	$clonedValue = clone $values;
			$reactiveOne+=$page->questions()->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')->where('question_id', $testOnePos)->sum('answer');
			//	$clonedValue = clone $values;
			$nonReactiveOne+=$page->questions()->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')->where('question_id', $testOneNeg)->sum('answer');
			//	$clonedValue = clone $values;
			$reactiveTwo+=$page->questions()->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')->where('question_id', $testTwoPos)->sum('answer');
			//	$clonedValue = clone $values;
			$nonReactiveTwo+=$page->questions()->join('htc_survey_page_data', 'htc_survey_page_questions.id', '=', 'htc_survey_page_data.htc_survey_page_question_id')->where('question_id', $testTwoNeg)->sum('answer');
		}
		$absReactive = abs($reactiveTwo-$reactiveOne);
		$absNonReactive = abs($nonReactiveTwo-$nonReactiveOne);
		return ($total - $invalid)>0?round(($reactiveTwo+$nonReactiveOne) * 100 / ($total-$invalid), 2):0;
	}
	/**
	* Return Sdp ID given the identifier
	* @param $name the identifier of the sdp
	*/
	public static function idById($id=NULL)
	{
		if($id!=NULL){
			try 
			{
				$sdp = Sdp::where('identifier', $id)->orderBy('name', 'asc')->firstOrFail();
				return $sdp->id;
			} catch (ModelNotFoundException $e) 
			{
				Log::error("The sdp with identifier ` $id ` does not exist:  ". $e->getMessage());
				//TODO: send email?
				return null;
			}
		}
		else{
			return null;
		}
	}
	/**
	* Function to return counts of data submiited
	*/
	public function submissions($id, $check)
	{
		$ssdps = $this->surveys()->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
					  ->where('facility_id', $id)
					  ->where('checklist_id', $check)
					  ->count();
		return $ssdps;
	}
}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class Section extends Model implements Revisionable {
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'sections';
	use RevisionableTrait;

    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    protected $revisionable = [
        'name',
        'description',
        'user_id',
    ];

	/**
	 * Questions relationship
	 */
	public function questions()
	{
		return $this->hasMany('App\Models\Question');
	}
	/**
	 * Checklist relationship
	 */
	public function checklist()
	{
		return $this->belongsTo('App\Models\Checklist');
	}
	/**
	 * Check if section has scorable questions
	 */
	public function isScorable()
	{
		$array = array();
		foreach ($this->questions as $question) {
			$answers = array();
			if($question->answers->count()>0)
			{	
				foreach ($question->answers as $response) 
				{
					if($response->score>0)
						array_push($answers, $response->id);
				}
			}
			if(count($answers)>0)
				array_push($array, $question->id);
		}
		if(count($array)>0)
			return true;
		else
			return false;
	}
	/**
	 * Function to calculate scores per section
	 */
	public function spider($site = NULL, $sub_county = NULL, $county = NULL, $from = NULL, $to = NULL)
	{
		$points = 0.0;
		$array = array();
		foreach ($this->questions as $question)
		{
			if($question->answers->count()>0)
				array_push($array, $question);
		}
		$counter = 0;
		$checklist = Checklist::idByName('SPI-RT Checklist');
		foreach ($array as $question)
		{
			$values=SurveyQuestion::where('question_id', $question->id)
									->join('survey_sdps', 'survey_sdps.id', '=', 'survey_questions.survey_sdp_id')
									->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id');
									if($from && $to)
									{
										$values = $values->whereBetween('date_submitted', [$from, $to]);
									}
									if($county || $sub_county || $site)
									{
										if($sub_county || $site)
										{
											if(isset($site))
											{
												$values = $values->where('facility_id', $site);
												$counter = SurveySdp::join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
																	->where('checklist_id', $checklist)
																	->where('facility_id', $site)
																	->whereBetween('date_submitted', [$from, $to])->count();
											}
											else
											{
												$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
														 		 ->where('sub_county_id', $sub_county);
												$counter = SurveySdp::join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
																	->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
																	->where('checklist_id', $checklist)
																	->where('sub_county_id', $sub_county)
																	->whereBetween('date_submitted', [$from, $to])->count();
											}
										}
										else
										{
											$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
															 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
															 ->where('county_id', $county);
											$counter = SurveySdp::join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
																	->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
																	->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
																	->where('checklist_id', $checklist)
																	->where('county_id', $county)
																	->whereBetween('date_submitted', [$from, $to])->count();
										}
									}
									else
									{
										$counter = $values->count();
									}
			$values = $values->get(array('survey_questions.*'));
			foreach ($values as $key => $value) 
			{
				$points+=SurveyQuestion::find($value->id)->ss->score;
			}
		}
		return round($points*100/($this->total_points*$counter), 2);
	}
	/**
	 * Function to calculate the snapshot given section
	 */
	public function snapshot($county = null, $sub_county = null, $site = null, $from = NULL, $to = NULL)
	{
		//	Initialize variables
		$counter = 0;
		$checklist = Checklist::idByName('M & E Checklist');
		if($this->isScorable())
		{
			$total = 0.0;		
			foreach ($this->questions as $question)
			{
				if($question->answers->count()>0)
				{
					$values = SurveyQuestion::where('question_id', $question->id)
									->join('survey_sdps', 'survey_sdps.id', '=', 'survey_questions.survey_sdp_id')
									->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id');
					if($from && $to)
					{
						$values = $values->whereBetween('date_submitted', [$from, $to]);
					}
					if($county || $sub_county || $site)
					{
						if($sub_county || $site)
						{
							if(isset($site))
							{
								$values = $values->where('facility_id', $site);
								$counter = Facility::find($site)->surveys()->where('checklist_id', $checklist)->whereBetween('date_submitted', [$from, $to])->count();
							}
							else
							{
								$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
										 		 ->where('sub_county_id', $sub_county);
								foreach (SubCounty::find($sub_county)->facilities as $facility)
								{
									$counter+=$facility->surveys()->where('checklist_id', $checklist)->whereBetween('date_submitted', [$from, $to])->count();
								}
							}
						}
						else
						{
							$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
											 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
											 ->where('county_id', $county);
							foreach (County::find($county)->subCounties as $subCounty)
							{
								foreach ($subCounty->facilities as $facility)
								{
									$counter+=$facility->surveys()->where('checklist_id', $checklist)->whereBetween('date_submitted', [$from, $to])->count();
								}
							}
						}
					}
					else
					{
						$counter = $values->count();
					}
					$values = $values->get(array('survey_questions.*'));
					foreach ($values as $sq) 
					{
						$total+=$sq->ss->score;
					}
				}
			}
		}
		return $counter!=0?round(($total*100)/($counter*$this->total_points), 2):0.00;
	}
	/**
	 * Function to return color code given the percent value
	 */
	public static function color($percent)
	{
		$class = '';
		if($percent >= 0 && $percent <25)
			$class = 'does-not-exist';
		else if($percent >=25 && $percent <50)
			$class = 'in-development';
		else if($percent >=50 && $percent <75)
			$class = 'being-implemented';
		else if($percent >=75 && $percent <100)
			$class = 'completed';
		return $class;
	}
	/**
	 * Function to calculate number of specific responses
	 */
	public function column($county = null, $sub_county = null, $site = null, $from = NULL, $to = NULL)
	{
		//	Initialize variables
		$total = 0;
		foreach ($this->questions as $question) {
			$questions = array();
			$values=SurveyQuestion::where('question_id', $question->id)
									->join('survey_sdps', 'survey_sdps.id', '=', 'survey_questions.survey_sdp_id')
									->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id');
									if($from && $to)
									{
										$values = $values->whereBetween('date_submitted', [$from, $to]);
									}
									if($county || $sub_county || $site)
									{
										if($sub_county || $site)
										{
											if(isset($site))
											{
												$values = $values->where('facility_id', $site);
											}
											else
											{
												$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
														 		 ->where('sub_county_id', $sub_county);
											}
										}
										else
										{
											$values = $values->join('facilities', 'facilities.id', '=', 'surveys.facility_id')
															 ->join('sub_counties', 'sub_counties.id', '=', 'facilities.sub_county_id')
															 ->where('county_id', $county);
										}
									}
			$values = $values->get(array('survey_questions.*'));
			foreach ($values as $sq) {
				if($sq->sd->answer)
					$total++;
			}
		}
		return $total;
	}
	/**
	 * Function to calculate percentage based on quarters
	 */
	public function quarter($period)
	{
		$checklist = $this->checklist;
		$survey_sdp_ids = NULL;
		$counter = 0;
		if($period === 'Baseline')
		{
			$survey_sdp_ids = SpirtInfo::lists('survey_sdp_id');
			$counter = count($survey_sdp_ids);
		}
		else
		{
			$start_date = NULL;
			$end_date = NULL;
			if(substr($period, 8) == '1')
			{
				$start_date = date('Y-10-01');
				$end_date = date('Y-12-31');
			}
			else if(substr($period, 8) === '2')
			{
				$start_date = date('Y-01-01');
				$end_date = date('Y-03-31');	
			}
			else if(substr($period, 8) === '3')
			{
				$start_date = date('Y-04-01');
				$end_date = date('Y-06-30');	
			}
			else if(substr($period, 8) === '3')
			{
				$start_date = date('Y-07-01');
				$end_date = date('Y-09-30');	
			}
			$survey_sdp_ids = SpirtInfo::join('survey_sdps', 'survey_sdps.id', '=', 'survey_spirt_info.survey_sdp_id')
										->join('surveys', 'surveys.id', '=', 'survey_sdps.survey_id')
										->whereBetween('date_started', [$start_date, $end_date])
										->lists('survey_spirt_info.survey_sdp_id');
			$counter = count($survey_sdp_ids);
		}
		if($this->isScorable())
		{
			$total = 0.0;		
			foreach ($this->questions as $question)
			{
				if($question->answers->count()>0)
				{
					foreach ($question->sqs as $sq) 
					{
						if(in_array($sq->survey_sdp_id, $survey_sdp_ids))
							$total+=$sq->ss->score;
					}
				}
			}
			return $counter!=0?round(($total*100)/($this->total_points*$counter), 2):0.00;
		}
	}
}
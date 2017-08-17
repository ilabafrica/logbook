<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMflTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//	User tiers - county/sub-county/facility
		Schema::create('user_tiers', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('role_id')->unsigned();
			$table->tinyInteger('tier');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unique(array('user_id','role_id'));

            $table->softDeletes();
			$table->timestamps();
		});
		//	Facility Types
		Schema::create('facility_types', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Facility Owners
		Schema::create('facility_owners', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Counties
		Schema::create('counties', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('hq', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	sub-counties
		Schema::create('sub_counties', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->integer('county_id')->unsigned();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('county_id')->references('id')->on('counties');

            $table->softDeletes();
			$table->timestamps();
		});		
		//	Facilities
		Schema::create('facilities', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('code', 20)->nullable();
			$table->string('name', 100);
			$table->integer('sub_county_id')->unsigned(); 
			$table->integer('facility_type_id')->unsigned();
			$table->integer('facility_owner_id')->unsigned();
			$table->string('reporting_site', 100);
			$table->string('nearest_town', 50);
			$table->string('landline', 50);
			$table->string('mobile', 50);
			$table->string('email', 50);
			$table->string('address', 50);
			$table->string('in_charge', 50);
			$table->string('operational_status', 2);
			$table->integer('longitude')->unsigned();
			$table->integer('latitude')->unsigned();
			$table->integer('user_id')->unsigned();

            $table->foreign('facility_type_id')->references('id')->on('facility_types');
            $table->foreign('facility_owner_id')->references('id')->on('facility_owners');
            $table->foreign('sub_county_id')->references('id')->on('sub_counties');
            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Site Types
		Schema::create('site_types', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Facilities
		Schema::create('sites', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('facility_id')->unsigned();
			$table->integer('site_type_id')->unsigned();
			$table->string('local_id', 100);
			$table->string('name', 100);
			$table->string('department', 50);
			$table->string('mobile', 50);
			$table->string('email', 50);
			$table->string('in_charge', 50);
			$table->integer('user_id')->unsigned();

            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('site_type_id')->references('id')->on('site_types');
            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Approval agencies
		Schema::create('agencies', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Test kits
		Schema::create('test_kits', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('full_name', 100);
			$table->string('short_name', 100);
			$table->string('manufacturer', 100);
			$table->integer('approval_status')->unsigned();
			$table->integer('approval_agency_id')->unsigned();
			$table->tinyInteger('incountry_approval');
			$table->integer('user_id')->unsigned();

			$table->foreign('approval_agency_id')->references('id')->on('agencies');
            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});		
		//	Site test kits
		Schema::create('site_test_kits', function(Blueprint $table)

			{
			$table->increments('id')->unsigned();
			$table->integer('site_id')->unsigned();
			$table->integer('kit_id')->unsigned();
			$table->string('lot_no', 100);
			$table->date('expiry_date')->nullable();
			$table->string('comments', 100);
			$table->tinyInteger('stock_available');
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('kit_id')->references('id')->on('test_kits');


            $table->softDeletes();
			$table->timestamps();
		});
	
		//sdps
		Schema::create('sdps', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->string('identifier', 100)->nullable();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Algorithm data
		/*Schema::create('htc', function(Blueprint $table)
		{

			$table->increments('id')->unsigned();			
			$table->integer('site_id')->unsigned();
			$table->integer('book_no')->unsigned();
			$table->integer('page_no')->unsigned();
			$table->integer('algorithm')->unsigned();
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->integer('positive');
			$table->integer('negative');
			$table->integer('indeterminate');
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->softDeletes();
			$table->timestamps();
		});
		//	Totals as counted by data officer
		Schema::create('htc_test_kits', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('htc_id')->unsigned();
			$table->integer('site_test_kit_id')->unsigned();
			$table->integer('reactive');
			$table->integer('non_reactive');
			$table->integer('invalid');
			$table->tinyInteger('test_kit_no');

            $table->foreign('htc_id')->references('id')->on('htc');
            $table->foreign('site_test_kit_id')->references('id')->on('site_test_kits');
            $table->softDeletes();
			$table->timestamps();
		});		
	}*/

	//tables dealing with survey
	//	checklists
		Schema::create('checklists', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});

		//	Field Groups - Sections
		Schema::create('sections', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('label')->nullable();
			$table->string('description', 100);
			$table->integer('checklist_id')->unsigned();
			$table->smallInteger('total_points')->nullable();
			$table->smallInteger('order')->nullable();
			$table->integer('user_id')->unsigned();

            $table->foreign('checklist_id')->references('id')->on('checklists');
            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});

		//	Fields - Questions
		Schema::create('questions', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('section_id')->unsigned();
			$table->string('name')->nullable();
			$table->string('identifier')->nullable();
			$table->string('title')->nullable();
			$table->text('description')->nullable();			
			$table->tinyInteger('question_type');
			$table->integer('required')->nullable();
			$table->string('info')->nullable();
			$table->string('comment')->nullable();
			$table->integer('score')->nullable();
			$table->integer('user_id')->unsigned();
			

			$table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});

		//	possible responses to the questions
		Schema::create('responses', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->float('score')->nullable();
            $table->float('range_lower')->nullable();
            $table->float('range_upper')->nullable();
            $table->string('identifier', 100)->nullable();
            $table->integer('user_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        //	actual responses to the questions
		Schema::create('question_responses', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('question_id')->unsigned();
			$table->integer('response_id')->unsigned();
			$table->string('comment')->nullable();
           
            $table->foreign('question_id')->references('id')->on('questions');
            $table->foreign('response_id')->references('id')->on('responses');

            $table->unique(array('question_id','response_id'));

            $table->softDeletes();
			$table->timestamps();

		});

		//	survey
		Schema::create('surveys', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('qa_officer');
			$table->integer('facility_id')->unsigned();
			$table->string('longitude')->nullable();
			$table->string('latitude')->nullable();
			$table->integer('checklist_id')->unsigned();
			$table->string('comment')->nullable();
            $table->dateTime('date_started')->nullable();
            $table->dateTime('date_ended')->nullable();
            $table->dateTime('date_submitted')->nullable();

            $table->foreign('checklist_id')->references('id')->on('checklists');
            $table->foreign('facility_id')->references('id')->on('facilities');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Survey-sdps
		Schema::create('survey_sdps', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('survey_id')->unsigned();
			$table->integer('sdp_id')->unsigned();
            $table->string('comment')->nullable();

			$table->foreign('survey_id')->references('id')->on('surveys');
            $table->foreign('sdp_id')->references('id')->on('sdps');
            $table->unique(array('survey_id', 'sdp_id', 'comment'));

            $table->softDeletes();
			$table->timestamps();
		});
		//	htc-survey-sdp-pages
		Schema::create('htc_survey_pages', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('survey_sdp_id')->unsigned();
			$table->integer('page');

			$table->foreign('survey_sdp_id')->references('id')->on('survey_sdps');

            $table->softDeletes();
			$table->timestamps();
		});
		//	htc-survey-sdp-page-questions
		Schema::create('htc_survey_page_questions', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('htc_survey_page_id')->unsigned();
			$table->integer('question_id')->unsigned();

			$table->foreign('htc_survey_page_id')->references('id')->on('htc_survey_pages');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->unique(array('htc_survey_page_id', 'question_id'));

            $table->softDeletes();
			$table->timestamps();
		});

		//	htc-survey-page-data
		Schema::create('htc_survey_page_data', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();			
			$table->integer('htc_survey_page_question_id')->unsigned();
			$table->string('answer');
			$table->string('comment')->nullable();

            $table->foreign('htc_survey_page_question_id')->references('id')->on('htc_survey_page_questions');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Survey-questions
		Schema::create('survey_questions', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('survey_sdp_id')->unsigned();
			$table->integer('question_id')->unsigned();

			$table->foreign('survey_sdp_id')->references('id')->on('survey_sdps');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->unique(array('survey_sdp_id', 'question_id'));

            $table->softDeletes();
			$table->timestamps();
		});
		//	survey_data
		Schema::create('survey_data', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();			
			$table->integer('survey_question_id')->unsigned();
			$table->string('answer');
			$table->string('comment')->nullable();

            $table->foreign('survey_question_id')->references('id')->on('survey_questions');

            $table->softDeletes();
			$table->timestamps();
		});
		//	survey_scores
		Schema::create('survey_scores', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();	
			$table->integer('survey_question_id')->unsigned();
			$table->float('score');

            $table->foreign('survey_question_id')->references('id')->on('survey_questions');

            $table->softDeletes();
			$table->timestamps();
		});

		//hiv test kits 
		Schema::create('hiv_test_kits', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Audit Types
		Schema::create('cadres', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});

		//affiliations
		Schema::create('affiliations', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->integer('user_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
        //algorithms
		Schema::create('algorithms', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->integer('user_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
        //audit_types
		Schema::create('audit_types', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->integer('user_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
        //	survey_me_info
		Schema::create('survey_me_info', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('survey_sdp_id')->unsigned();
            $table->integer('audit_type_id')->unsigned();
            $table->integer('algorithm_id')->unsigned();
            $table->integer('screening')->unsigned();
            $table->integer('confirmatory')->unsigned();
            $table->integer('tie_breaker')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('survey_sdp_id')->references('id')->on('survey_sdps');
            $table->foreign('audit_type_id')->references('id')->on('audit_types');
            $table->foreign('algorithm_id')->references('id')->on('algorithms');
            $table->foreign('screening')->references('id')->on('hiv_test_kits');
            $table->foreign('confirmatory')->references('id')->on('hiv_test_kits');
            $table->foreign('tie_breaker')->references('id')->on('hiv_test_kits');
        });
        //	survey_spirt_info
		Schema::create('survey_spirt_info', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('survey_sdp_id')->unsigned();
            $table->integer('affiliation_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('survey_sdp_id')->references('id')->on('survey_sdps');
            $table->foreign('affiliation_id')->references('id')->on('affiliations');
        });
        //	survey_spirt_comments
		Schema::create('survey_spirt_comments', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('survey_sdp_id')->unsigned();
            $table->smallInteger('section_id');
            $table->text('comments');

            $table->softDeletes();
            $table->foreign('survey_sdp_id')->references('id')->on('survey_sdps');
        });
        //	Levels
		Schema::create('levels', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->float('range_lower')->nullable();
            $table->float('range_upper')->nullable();
            $table->integer('user_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('facilities');
		Schema::dropIfExists('facility_types');
		Schema::dropIfExists('facility_owners');
		Schema::dropIfExists('sub_counties');
		Schema::dropIfExists('counties');
		Schema::dropIfExists('site_types');
		Schema::dropIfExists('sites');
		Schema::dropIfExists('test_kits');
		Schema::dropIfExists('agencies');
		Schema::dropIfExists('site_test_kits');
		Schema::dropIfExists('htc');
		Schema::dropIfExists('totals');
		Schema::dropIfExists('checklists');
		Schema::dropIfExists('sections');
		Schema::dropIfExists('questions');
		Schema::dropIfExists('responses');
		Schema::dropIfExists('question_responses');
		Schema::dropIfExists('survey');
		Schema::dropIfExists('survey_data');
		Schema::dropIfExists('survey_scores');
		Schema::dropIfExists('sdps');
		Schema::dropIfExists('hiv_test_kits');
		Schema::dropIfExists('cadres');
		Schema::dropIfExists('algorithms');
		Schema::dropIfExists('affiliations');
		Schema::dropIfExists('audit_types');
	}
}

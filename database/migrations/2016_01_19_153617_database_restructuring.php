<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatabaseRestructuring extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//	Create tiers tables to hold the likes of OPD1, OPD2 ...
		Schema::create('tiers', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description', 100);
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Create tiers tables to link sdps to ties
		Schema::create('sdp_tiers', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('sdp_id')->unsigned();
			$table->integer('tier_id')->unsigned();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('sdp_id')->references('id')->on('sdps');
            $table->foreign('tier_id')->references('id')->on('tiers');
            $table->unique(array('sdp_id','tier_id'));

            $table->softDeletes();
			$table->timestamps();
		});
		//	Create facility_sdps
		Schema::create('facility_sdps', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('facility_id')->unsigned();
			$table->integer('sdp_id')->unsigned();
			$table->tinyInteger('sdp_tier_id')->nullable();
			$table->integer('user_id')->unsigned();

            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('sdp_id')->references('id')->on('sdps');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(array('facility_id','sdp_id', 'sdp_tier_id'));

            $table->timestamps();
            $table->softDeletes();
        });
		/*//	Drop table survey_scores
		Schema::dropIfExists('survey_scores');
		//	Drop table survey_spirt_comments
		Schema::dropIfExists('survey_spirt_comments');
		//	Drop table survey_me_info
		Schema::dropIfExists('survey_me_info');
		//	Drop table test_kits
		Schema::dropIfExists('test_kits');
		//	Drop table site_test_kits
		Schema::dropIfExists('site_test_kits');
		//	Drop table site_types
		Schema::dropIfExists('site_types');
		//	Drop table sites
		Schema::dropIfExists('sites');
		//	Drop table agencies
		Schema::dropIfExists('agencies');
		//	Drop table survey-spirt-info
		Schema::dropIfExists('survey_spirt_info');
		// 	Alter survey-sdps table
		Schema::table('survey_questions', function($table)
		{
			$table->dropColumn('survey_sdp_id');
			$table->integer('survey_id')->unsigned()->after('id');
			$table->foreign('survey_id')->references('id')->on('surveys');
		});
		// 	Alter htc-survey-pages table
		Schema::table('htc_survey_pages', function($table)
		{
			$table->dropColumn('survey_sdp_id');
			$table->integer('survey_id')->unsigned()->after('id');
			$table->foreign('survey_id')->references('id')->on('surveys');
		});
		// 	Alter surveys table
		Schema::table('surveys', function($table)
		{
			$table->dropColumn('facility_id');
			$table->dropColumn('qa_officer');
			$table->integer('qa_officer_id')->unsigned()->after('id');
		    $table->integer('facility_sdp_id')->unsigned()->after('qa_officer_id');
		    $table->foreign('qa_officer_id')->references('id')->on('qa_officers');
		    $table->foreign('facility_sdp_id')->references('id')->on('facility_sdps');
		});
		//	Drop table survey-sdps
		Schema::dropIfExists('survey_sdps');*/
		// 	Alter users
		Schema::table('users', function($table)
		{
			$table->string('code')->nullable()->unique()->after('address');
		});
		// 	Alter counties
		Schema::table('counties', function($table)
		{
			$table->string('code')->after('hq');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//	Drop table facility_sdps
		Schema::dropIfExists('facility_sdps');
	}
}

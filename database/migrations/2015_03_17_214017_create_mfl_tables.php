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
		//	Constituencies
		Schema::create('constituencies', function(Blueprint $table)
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
			$table->string('code', 20);
			$table->string('name', 100);
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
			$table->string('site_id', 100);
			$table->string('site_name', 100);
			$table->string('site_type_id')->unsigned();
			$table->string('address', 50);
			$table->string('nearest_town', 50);
			$table->string('county_id')->unsigned();
			$table->string('department', 50);
			$table->string('landline', 50);
			$table->string('mobile', 50);
			$table->string('email', 50);
			$table->string('in_charge', 50);
			$table->integer('longitude')->unsigned();
			$table->integer('latitude')->unsigned();
			$table->integer('user_id')->unsigned();

            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('site_type_id')->references('id')->on('site_types');
            $table->foreign('county_id')->references('id')->on('counties');
            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});

		//	kitnames
		Schema::create('test_kits', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('full_testkit_name', 100);
			$table->string('kit_name', 100);
			$table->string('manufacturer', 100);
			$table->string('approval_status')->unsigned();
			$table->string('approval_agency', 100);
			$table->string('incountry_approval')->unsigned();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
			$table->timestamps();
		});
		//	Site Types
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
		Schema::dropIfExists('constituencies');
		Schema::dropIfExists('counties');
		Schema::dropIfExists('site_types');
		Schema::dropIfExists('sites');
		Schema::dropIfExists('test_kits');
		Schema::dropIfExists('agencies');
		
	}

}

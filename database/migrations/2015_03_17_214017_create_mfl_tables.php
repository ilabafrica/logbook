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
			$table->integer('site_type_id')->unsigned();
			$table->string('address', 50);
			$table->string('nearest_town', 50);
			$table->integer('county_id')->unsigned();
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
			$table->integer('approval_status')->unsigned();
			$table->integer('approval_agency_id')->unsigned();
			$table->integer('incountry_approval')->unsigned();
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
		//	assign_testkits
		Schema::create('assign_testkits', function(Blueprint $table)
		{
			$table->increments('site_name_id')->unsigned();
			$table->integer('kit_name_id ')->unsigned();
			$table->string('lot_no', 100);
			$table->dateTime('expiry_date')->nullable();
			$table->string('comments', 100);
			$table->integer('stock_avl')->unsigned();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('site_name_id')->references('id')->on('sites');
            $table->foreign('kit_name_id ')->references('id')->on('test_kits');


            $table->softDeletes();
			$table->timestamps();
		});
		//	serials
		Schema::create('serials', function(Blueprint $table)
		{
			$table->increments('test_site_id')->unsigned();
			$table->integer('book_no')->unsigned();
			$table->integer('page_no')->unsigned();
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->integer('test_kit1_id')->unsigned();
			$table->integer('test_kit2_id')->unsigned();
			$table->integer('test_kit3_id')->unsigned();			
			$table->integer('test_kit1R')->unsigned();
			$table->integer('test_kit1NR')->unsigned();
			$table->integer('test_kit1Inv')->unsigned();
			$table->integer('test_kit2R')->unsigned();
			$table->integer('test_kit2NR')->unsigned();
			$table->integer('test_kit2Inv')->unsigned();
			$table->integer('test_kit3R')->unsigned();
			$table->integer('test_kit3NR')->unsigned();
			$table->integer('test_kit3Inv')->unsigned();
			$table->integer('positive')->unsigned();
			$table->integer('negative')->unsigned();
			$table->integer('indeterminate')->unsigned();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('test_site_id')->references('id')->on('sites');
            $table->foreign('test_kit1_id ')->references('id')->on('test_kits');
            $table->foreign('test_kit2_id ')->references('id')->on('test_kits');
            $table->foreign('test_kit3_id ')->references('id')->on('test_kits');
            $table->softDeletes();
			$table->timestamps();
		});
//	serials
		Schema::create('parallels', function(Blueprint $table)
		{
			$table->increments('test_site_id')->unsigned();
			$table->integer('book_no')->unsigned();
			$table->integer('page_no')->unsigned();
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->integer('test_kit1_id')->unsigned();
			$table->integer('test_kit2_id')->unsigned();
			$table->integer('test_kit3_id')->unsigned();			
			$table->integer('test_kit1R')->unsigned();
			$table->integer('test_kit1NR')->unsigned();
			$table->integer('test_kit1Inv')->unsigned();
			$table->integer('test_kit2R')->unsigned();
			$table->integer('test_kit2NR')->unsigned();
			$table->integer('test_kit2Inv')->unsigned();
			$table->integer('test_kit3R')->unsigned();
			$table->integer('test_kit3NR')->unsigned();
			$table->integer('test_kit3Inv')->unsigned();
			$table->integer('positive')->unsigned();
			$table->integer('negative')->unsigned();
			$table->integer('indeterminate')->unsigned();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('test_site_id')->references('id')->on('sites');
            $table->foreign('test_kit1_id ')->references('id')->on('test_kits');
            $table->foreign('test_kit2_id ')->references('id')->on('test_kits');
            $table->foreign('test_kit3_id ')->references('id')->on('test_kits');
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
		Schema::dropIfExists('assign_testkits');
		Schema::dropIfExists('serials');
		Schema::dropIfExists('parallels');
		
	}

}

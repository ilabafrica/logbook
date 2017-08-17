<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PtEnrollmentTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create table for storing designation
        Schema::create('designations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
        // Create table for storing pt programs
        Schema::create('pts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
			$table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
        // Create table for storing pt data
        Schema::create('pt_enrollments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('qa_officer')->nullable();
            $table->dateTime('start_time');
            $table->integer('facility_id')->unsigned();
            $table->string('mfl_code')->nullable();
			$table->integer('pt_id')->unsigned();
			$table->string('tester')->nullable();
			$table->string('tester_phone')->nullable();
			$table->string('tester_email')->nullable();
			$table->integer('designation_id')->unsigned();
			$table->string('in_charge')->nullable();
			$table->dateTime('date_submitted');
			$table->dateTime('data_month');

            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('pt_id')->references('id')->on('pts');
            $table->foreign('designation_id')->references('id')->on('designations');

            $table->timestamps();
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//	Reverse migrations
		Schema::drop('pt_enrollments');
		Schema::drop('pts');
		Schema::drop('designations');
	}
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('role_id')->unsigned();
			$table->integer('county_id')->nullable();
			$table->integer('sub_county_id')->nullable();
			$table->integer('facility_id')->nullable();
			$table->string('name');
			$table->tinyInteger("gender")->default(0);
			$table->string('email')->unique();
			$table->string('phone');
			$table->string('address')->unique();
			$table->string("username", 50)->unique();
			$table->string('password', 60);
			$table->string("image", 100)->nullable();
			$table->rememberToken();


			$table->foreign('role_id')->references('id')->on('roles');
			$table->foreign('county_id')->references('id')->on('counties');
			$table->foreign('sub_county_id')->references('id')->on('sub_counties');
			$table->foreign('fscility_id')->references('id')->on('facilities');

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
		Schema::dropIfExists('users');
	}

}

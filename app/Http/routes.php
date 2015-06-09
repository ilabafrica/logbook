<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
/* Routes accessible before logging in */
Route::group(array("before" => "guest"), function()
{
	Route::any('/', array(
	    "as" => "auth.login",
	    "uses" => "WelcomeController@index"
	));

    Route::any('user/login', array(
	    "as" => "user.login",
	    "uses" => "Auth\AuthController@postLogin"
	));
    
});

//	User controller
Route::resource('user', 'UserController');
Route::get("/user/{id}/delete", array(
    "as"   => "user.delete",
    "uses" => "UserController@delete"
));



//	Facility controller
Route::resource('facility', 'FacilityController');
Route::get("/facility/{id}/delete", array(
    "as"   => "facility.delete",
    "uses" => "FacilityController@delete"
));

//	Facility Types controller
Route::resource('facilityType', 'FacilityTypeController');
Route::get("/facilityType/{id}/delete", array(
    "as"   => "facilityType.delete",
    "uses" => "FacilityTypeController@delete"
));


//	Facility owners controller
Route::resource('facilityOwner', 'FacilityOwnerController');
Route::get("/facilityOwner/{id}/delete", array(
    "as"   => "facilityOwner.delete",
    "uses" => "FacilityOwnerController@delete"
));


//	Job titles controller
Route::resource('title', 'TitleController');
Route::get("/title/{id}/delete", array(
    "as"   => "title.delete",
    "uses" => "TitleController@delete"
));



//	County controller
Route::resource('county', 'CountyController');
Route::get("/county/{id}/delete", array(
    "as"   => "county.delete",
    "uses" => "CountyController@delete"
));

//	Constituency controller
Route::resource('constituency', 'ConstituencyController');

Route::get("/constituency/{id}/delete", array(
    "as"   => "constituency.delete",
    "uses" => "ConstituencyController@delete"
));



//	Towns controller
Route::resource('town', 'TownController');
Route::get("/town/{id}/delete", array(
    "as"   => "town.delete",
    "uses" => "TownController@delete"
));

//	Lab Levels controller
Route::resource('labLevel', 'LabLevelController');
Route::get("/labLevel/{id}/delete", array(
    "as"   => "labLevel.delete",
    "uses" => "LabLevelController@delete"
));




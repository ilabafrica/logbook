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
Route::get("/import/facility", array(
    "as"   => "facility.import",
    "uses" => "FacilityController@import"
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

//	County controller
Route::resource('county', 'CountyController');
Route::get("/county/{id}/delete", array(
    "as"   => "county.delete",
    "uses" => "CountyController@delete"
));

//	SubCounty controller
Route::resource('subCounty', 'SubCountyController');

Route::get("/subCounty/{id}/delete", array(
    "as"   => "subCounty.delete",
    "uses" => "SubCountyController@delete"
));

//	ImportFacilityData
Route::resource('importfacilitydata', 'ImportFacilityDataController');
//ImportTestKit
Route::resource('importtestkit', 'ImportTestKitController');
//testkit
Route::resource('testKit', 'TestKitController');
Route::get("/testKit/{id}/delete", array(
    "as"   => "testKit.delete",
    "uses" => "TestKitController@delete"
));
Route::get("/import/testKit", array(
    "as"   => "testKit.import",
    "uses" => "TestKitController@import"
));
//site
Route::resource('site', 'SiteController');
//assign testkit
Route::resource('siteKit', 'SiteKitController');
//result
Route::resource('result', 'ResultController');

//dataentry
Route::resource('dataentry', 'DataEntryController');
//serial
Route::resource('serial', 'SerialController');
Route::get("/serial/{id}/index", array(
    "as"   => "serial.index",
    "uses" => "SerialController@index"));
//summaryserial
Route::resource('summaryserial', 'SummarySerialController');


//parallel
Route::resource('parallel', 'ParallelController');
Route::get("/parallel/{id}/index", array(
    "as"   => "parallel.index",
    "uses" => "ParallelController@index"));

//summaryparallel
Route::resource('summaryparallel', 'SummaryParallelController');

//logbookdata
Route::resource('logbookdata', 'LogbookDataController');
//trendreport
Route::resource('trendreport', 'TrendReportController');
//testkituse
Route::resource('testkituse', 'TestkitUseController');
//invalidresults
Route::resource('invalidresults', 'InvalidResultsController');
//customreport
Route::resource('customreport', 'CustomReportController');

//  Site Types controller
Route::resource('siteType', 'SiteTypeController');
Route::get("/siteType/{id}/delete", array(
    "as"   => "siteType.delete",
    "uses" => "SiteTypeController@delete"
));

//  Site Types controller
Route::resource('agency', 'AgencyController');
Route::get("/agency/{id}/delete", array(
    "as"   => "agency.delete",
    "uses" => "AgencyController@delete"
));

//  Role controller
Route::resource('role', 'RoleController');

//  Permission controller
Route::resource('permission', 'PermissionController');

//  Privilege controller
Route::resource('privilege', 'PrivilegeController');
//  Authorization controller
Route::resource('authorization', 'AuthorizationController');
//  HTC
//Route::resource('htc', 'HtcController');
Route::get("/htc/{id}/", array(
    "as"   => "htc.index",
    "uses" => "HtcController@index"
));
Route::get("/htc/{id}/create", array(
    "as"   => "htc.create",
    "uses" => "HtcController@create"
));
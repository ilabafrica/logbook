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


//  Facility Types controller
Route::resource('facilityType', 'FacilityTypeController');
Route::get("/facilityType/{id}/delete", array(
    "as"   => "facilityType.delete",
    "uses" => "FacilityTypeController@delete"
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

//nationalreport
Route::resource('nationalreport', 'NationalReportController');
//countyreport
Route::resource('countyreport', 'CountyReportController');
//subcountyreport
Route::resource('subCountyreport', 'SubCountyReportController');
//countyreport
Route::resource('facilityreport', 'FacilityReportController');


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
Route::post("/htc/{id}/saveLogbook", array(
    "as"   => "htc.saveLogbook",
    "uses" => "HtcController@store"
));
Route::get("/htc/{id}/{htc}/edit", array(
    "as"   => "htc.edit",
    "uses" => "HtcController@edit"
));
Route::get("/htc/{id}/{htc}/show", array(
    "as"   => "htc.show_source()",
    "uses" => "HtcController@show"
));
Route::post("/htc/{id}/updateLogbook", array(
    "as"   => "htc.updateLogbook",
    "uses" => "HtcController@update"
));
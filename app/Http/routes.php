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
Route::any('/home', 'ReportController@dashboard');
Route::any('/dashboard', 'ReportController@dashboard');

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

/* Routes accessible after logging in */
Route::group(['middleware' => 'auth'], function(){
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
    Route::get("/site/{id}/delete", array(
        "as"   => "site.delete",
        "uses" => "SiteController@delete"
    ));
    //assign testkit
    Route::resource('siteKit', 'SiteKitController');
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

    //  Level controller
    Route::resource('level', 'LevelController');

    //  Permission controller
    Route::resource('permission', 'PermissionController');

    //  Privilege controller
    Route::resource('privilege', 'PrivilegeController');
    //  Authorization controller
    Route::resource('authorization', 'AuthorizationController');

    //  Review controller
    Route::resource('review', 'ReviewController');
    //  Pt-program controller
    Route::resource('pt', 'PtController');
    //  Designation controller
    Route::resource('designation', 'DesignationController');
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

    /* Checklists */
    Route::resource('checklist', 'ChecklistController');
    Route::get("/checklist/{id}/delete", array(
        "as"   => "checklist.delete",
        "uses" => "ChecklistController@delete"
    ));
    /* Sections */
    Route::resource('section', 'SectionController');
    Route::get("/section/{id}/delete", array(
        "as"   => "section.delete",
        "uses" => "SectionController@delete"
    ));
    /* Questions */
    Route::resource('question', 'QuestionController');
    Route::get("/question/{id}/delete", array(
        "as"   => "question.delete",
        "uses" => "QuestionController@delete"
    ));
    /* Responses */
    Route::resource('response', 'ResponseController');
    Route::get("/response/{id}/delete", array(
        "as"   => "response.delete",
        "uses" => "ResponseController@delete"
    ));
    /* Survey */
    Route::get('survey', array(
        "as"    =>  "surveys",
        "uses"  =>  "SurveyController@index"
    ));
    Route::get('survey/{id}/create', array(
        "as"    =>  "survey.create",
        "uses"  =>  "SurveyController@create"
    ));
    Route::post('survey/save', array(
        "as"    =>  "survey.store",
        "uses"  =>  "SurveyController@store"
    ));
    Route::get('survey/{id}/list', array(
        "as"    =>  "survey.list",
        "uses"  =>  "SurveyController@listing"
    ));
    Route::get('survey/{id}/edit', array(
        "as"    =>  "survey.edit",
        "uses"  =>  "SurveyController@edit"
    ));
    Route::any('survey/{id}/update', array(
        "as"    =>  "survey.update",
        "uses"  =>  "SurveyController@update"
    ));
    Route::any('survey/{id}/delete', array(
        "as"    =>  "survey.delete",
        "uses"  =>  "SurveyController@delete"
    ));

    Route::get('survey/{id}', array(
        "as"    =>  "survey.show",
        "uses"  =>  "SurveyController@show"
    ));
    Route::get('survey/{id}/summary', array(
        "as"    =>  "survey.summary",
        "uses"  =>  "SurveyController@summary"
    ));
    Route::get('survey/{id}/collection', array(
        "as"    =>  "survey.collection",
        "uses"  =>  "SurveyController@collection"
    ));
    Route::get('survey/{id}/participant', array(
        "as"    =>  "survey.participant",
        "uses"  =>  "SurveyController@participant"
    ));
    Route::get('survey/{id}/county', array(
        "as"    =>  "survey.county.summary",
        "uses"  =>  "SurveyController@county"
    ));
    Route::get('survey/{id}/subcounty', array(
        "as"    =>  "survey.subcounty.summary",
        "uses"  =>  "SurveyController@subcounty"
    ));
    Route::get('survey/{id}/sdp', array(
        "as"    =>  "survey.sdp.summary",
        "uses"  =>  "SurveyController@sdp"
    ));
    /* Reports */
    Route::get('report', array(
        "as"    =>  "reports",
        "uses"  =>  "SurveyController@index"
    ));
    Route::any('report/{id}', array(
        "as"    =>  "reports.percent.positive",
        "uses"  =>  "ReportController@index"
    ));
    Route::any('report/{id}/agreement', array(
        "as"    =>  "reports.percent.agreement",
        "uses"  =>  "ReportController@agreement"
    ));
    Route::any('report/{id}/overall', array(
        "as"    =>  "reports.overall.agreement",
        "uses"  =>  "ReportController@overall"
    ));
    Route::any('report/{id}/invalid', array(
        "as"    =>  "reports.results.invalid",
        "uses"  =>  "ReportController@invalid"
    ));

    /* *
    *
    * Dynamic loading of sub-county filtered by county
    *
    */
    Route::any('/sub_county/dropdown', array(
        "as"    =>  "subCounty.dropdown",
        "uses"  =>  "CountyController@dropdown"
    ));
    Route::any('/facility/dropdown', array(
        "as"    =>  "facility.dropdown",
        "uses"  =>  "SubCountyController@dropdown"
    ));
    Route::any('/sdp/dropdown', array(
        "as"    =>  "sdp.dropdown",
        "uses"  =>  "FacilityController@dropdown"
    ));
    /* Algorithms */
    Route::resource('algorithm', 'AlgorithmController');
    /* Audit Types controller */
    Route::resource('auditType', 'AuditTypeController');
    Route::get("/auditType/{id}/delete", array(
        "as"   => "auditType.delete",
        "uses" => "AuditTypeController@delete"
    ));
    /* Affiliations controller */
    Route::resource('affiliation', 'AffiliationController');
    Route::get("/affiliation/{id}/delete", array(
        "as"   => "affiliation.delete",
        "uses" => "AffiliationController@delete"
    ));
    /* *
    *
    * M&E reports
    *
    */
    Route::any('report/{id}/me', array(
        "as"    =>  "reports.me.mscolumn",
        "uses"  =>  "ReportController@me"
    ));
    Route::any('report/{id}/stage', array(
        "as"    =>  "reports.me.stages",
        "uses"  =>  "ReportController@stage"
    ));
    /* *
    *
    * SPI-RT reports
    *
    */
    Route::any('report/{id}/spirt', array(
        "as"    =>  "reports.spirt.spider",
        "uses"  =>  "ReportController@spirt"
    ));
    /* *
    *
    * Local partner
    *
    */
    Route::any('report/{id}/periodic', array(
        "as"    =>  "reports.partner.periodic",
        "uses"  =>  "ReportController@periodic"
    ));
    Route::any('partner/accomplishment', array(
        "as"    =>  "reports.accomplishment",
        "uses"  =>  "ReportController@accomplishment"
    ));
    Route::any('partner/hr', array(
        "as"    =>  "reports.hr",
        "uses"  =>  "ReportController@hr"
    ));
    Route::any('partner/pt', array(
        "as"    =>  "reports.pt",
        "uses"  =>  "ReportController@pt"
    ));
    Route::any('partner/logbook', array(
        "as"    =>  "reports.logbook",
        "uses"  =>  "ReportController@logbook"
    ));
    Route::any('partner/logSdp', array(
        "as"    =>  "reports.logSdp",
        "uses"  =>  "ReportController@logSdp"
    ));
    Route::any('partner/logRegion', array(
        "as"    =>  "reports.logRegion",
        "uses"  =>  "ReportController@logRegion"
    ));
    Route::any('partner/spirt', array(
        "as"    =>  "reports.spirt",
        "uses"  =>  "ReportController@sprt"
    ));
    Route::any('partner/me', array(
        "as"    =>  "reports.eval",
        "uses"  =>  "ReportController@evaluation"
    ));
    Route::any('partner/period', array(
        "as"    =>  "reports.partner.period",
        "uses"  =>  "ReportController@period"
    ));
    Route::any('partner/region', array(
        "as"    =>  "reports.partner.region",
        "uses"  =>  "ReportController@region"
    ));
    Route::any('partner/sdp', array(
        "as"    =>  "reports.partner.sdp",
        "uses"  =>  "ReportController@sdp"
    ));
    Route::any('partner/ptSdp', array(
        "as"    =>  "reports.partner.ptSdp",
        "uses"  =>  "ReportController@ptSdp"
    ));
    Route::any('analysis/data', array(
        "as"    =>  "reports.data",
        "uses"  =>  "ReportController@data"
    ));
    Route::any('analysis/chart', array(
        "as"    =>  "reports.chart",
        "uses"  =>  "ReportController@chart"
    ));
    Route::any('analysis/snapshot', array(
        "as"    =>  "reports.snapshot",
        "uses"  =>  "ReportController@snapshot"
    ));
    Route::any('analysis/breakdown', array(
        "as"    =>  "reports.breakdown",
        "uses"  =>  "ReportController@breakdown"
    ));
    Route::any('analysis/response', array(
        "as"    =>  "reports.response",
        "uses"  =>  "ReportController@response"
    ));
    Route::any('api/{id?}', array(
        "as"    =>  "survey.import",
        "uses"  =>  "SurveyController@api"
    ));
    /**
    *
    *   Survey sdp routes
    *
    */
    Route::get('surveysdp/{id}', array(
        "as"    =>  "survey.sdp.show",
        "uses"  =>  "SurveyController@showSdp"
    ));
    Route::get('surveysdp/{id}/edit', array(
        "as"    =>  "survey.sdp.edit",
        "uses"  =>  "SurveyController@editSdp"
    ));
    Route::any('surveysdp/duplicate/{id?}', array(
        "as"    =>  "survey.sdp.duplicate",
        "uses"  =>  "SurveyController@duplicate"
    ));
    Route::any('surveysdp/{id}/update', array(
        "as"    =>  "survey.sdp.update",
        "uses"  =>  "SurveyController@updateSdp"
    ));
    Route::any('surveysdp/update/{id?}', array(
        "as"    =>  "survey.sdp.modal.edit",
        "uses"  =>  "SurveyController@modalUpdateSdp"
    ));
    /**
    *
    *   htc survey sdp pages routes
    *
    */
    Route::get('page/{id}', array(
        "as"    =>  "survey.sdp.page.show",
        "uses"  =>  "SurveyController@page"
    ));
    Route::get('page/{id}/delete', array(
        "as"    =>  "survey.sdp.page.delete",
        "uses"  =>  "SurveyController@deletePage"
    ));
    Route::get('page/{id}/edit', array(
        "as"    =>  "survey.sdp.page.edit",
        "uses"  =>  "SurveyController@editPage"
    ));
    Route::any('page/{id}/update', array(
        "as"    =>  "survey.sdp.page.update",
        "uses"  =>  "SurveyController@updatePage"
    ));
    /**
    *
    *   Download summaries in excel.
    *
    */
    Route::get('survey/{id}/collection/download', array(
        "as"    =>  "survey.collection.download",
        "uses"  =>  "SurveyController@collectionDownload"
    ));
    Route::get('survey/{id}/county/download', array(
        "as"    =>  "survey.county.download",
        "uses"  =>  "SurveyController@countyDownload"
    ));
    Route::get('survey/{id}/subcounty/download', array(
        "as"    =>  "survey.subcounty.download",
        "uses"  =>  "SurveyController@subcountyDownload"
    ));
    Route::get('survey/{id}/participant/download', array(
        "as"    =>  "survey.participant.download",
        "uses"  =>  "SurveyController@facilityDownload"
    ));
    Route::get('survey/{id}/sdp/download', array(
        "as"    =>  "survey.sdp.download",
        "uses"  =>  "SurveyController@sdpDownload"
    ));
    Route::any('survey/month/{id?}', array(
        "as"    =>  "survey.sdp.data",
        "uses"  =>  "SurveyController@dataMonth"
    ));
    Route::get('surveysdp/{id}/download', array(
        "as"    =>  "survey.sdp.download",
        "uses"  =>  "SurveyController@surveyDownload"
    ));
    Route::get('surveysdp/{id}/delete', array(
        "as"    =>  "survey.sdp.delete",
        "uses"  =>  "SurveyController@deleteSdp"
    ));
    Route::get('page/{id}/download', array(
        "as"    =>  "survey.sdp.page.download",
        "uses"  =>  "SurveyController@pageDownload"
    ));    
    Route::get('overview', array(
        "as"    =>  "survey.summary",
        "uses"  =>  "SurveyController@overview"
    ));    
    Route::any('report/{id}/programatic', array(
        "as"    =>  "report.programatic",
        "uses"  =>  "ReportController@overallOvertime"
    ));    
    Route::get('report/{id}/geographic', array(
        "as"    =>  "report.geographic",
        "uses"  =>  "ReportController@geographic"
    ));
    Route::any('partner/region', array(
        "as"    =>  "reports.partner.region",
        "uses"  =>  "ReportController@geoRegion"
    ));
    Route::any('partner/precert', array(
        "as"    =>  "reports.partner.precert",
        "uses"  =>  "ReportController@precert"
    ));
    Route::any('partner/overtime', array(
        "as"    =>  "reports.partner.overtime",
        "uses"  =>  "ReportController@precertOvertime"
    ));
    Route::any('partner/performance', array(
        "as"    =>  "reports.partner.performance",
        "uses"  =>  "ReportController@performance"
    ));
});
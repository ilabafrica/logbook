@section("sidebar")
<?php
	$active = array("","","","","","","","","");
	$key = explode("?",str_replace("/", "?", Request::path()));
	switch ($key[0]) {
		case 'home': $active[0] = "active"; break;
		case 'facility': $active[1] = "active"; break;
		case 'permission': $active[2] = "active"; break;
		case 'role':
		case 'privilege':
		case 'authorization': $active[3] = "active"; break;
		case 'user': 
		case 'facilityType': 
		case 'facilityOwner': 
		case 'title': 
		case 'county': 
		case 'constituency':
		case 'town':$active[4] = "active"; break;
		case 'labLevel': 
		case 'labAffiliation': 
		case 'labType':
		case 'lab':
		case 'auditType':
		case 'section':
		case 'auditField':
		case 'review':
		case 'answer':$active[5] = "active"; break;
		case 'note': 
		case 'assessment':
		case 'question': 
		
	}
?>

    <ul class="nav" id="side-menu">
        <li class="sidebar-search">
            <div class="input-group custom-search-form">
                <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
            <!-- /input-group -->
        </li>
        <!-- Checklist config -->
        <li>
            <a href="#"><i class="fa fa-sliders"></i> {!! Lang::choice('messages.checklist-config', 1) !!}<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{!! url('checklist') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.checklist', 2) !!}</a></li>
                <li><a href="{!! url('section') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.section', 2) !!}</a></li>
                <li><a href="{!! url('question') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.question', 2) !!}</a></li>
                <li><a href="{!! url('response') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.response', 2) !!}</a></li>
                <li><a href="{!! url('algorithm') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.algorithm', 2) !!}</a></li>
                <li><a href="{!! url('auditType') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.audit-type', 2) !!}</a></li>
                <li><a href="{!! url('affiliation') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.affiliation', 2) !!}</a></li>
            </ul>
        </li>
        <!-- Surveys -->
        <li>
            <a href="{!! url('survey') !!}"><i class="fa fa-edit fa-fw"></i> {!! Lang::choice('messages.survey', 1) !!}</a>
        </li>
        <!-- Reports -->
        <li>
            <a href="{!! url('report') !!}"><i class="fa fa-bar-chart-o fa-fw"></i> {!! Lang::choice('messages.report', 2) !!}</a>
        </li>
        <!-- Facility configuration -->
        <li>
            <a href="#"><i class="fa fa-database"></i> {!! Lang::choice('messages.facility-configuration', 1) !!}<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{!! url('facility') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.facility', 2) !!}</a></li>
                <li><a href="{!! url('facilityType') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.facility-type', 2) !!}</a></li>
                <li><a href="{!! url('facilityOwner') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.facility-owner', 2) !!}</a></li>
                <li><a href="{!! url('county') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.county', 2) !!}</a></li>
                <li><a href="{!! url('subCounty') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.sub-county', 2) !!}</a></li>
            </ul>
        </li>
        <!-- Site catalog -->
        <li>
            <a href="#"><i class="fa fa-stack-exchange"></i> {!! Lang::choice('messages.site-catalog', 1) !!}<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{!! url('site') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.site', 2) !!}</a></li>
                <li><a href="{!! url('siteType') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.site-type', 2) !!}</a></li>
            </ul>
        </li>
        <!-- Test kits -->
        <li>
            <a href="{!! url('testKit') !!}"><i class="fa fa-book"></i> {!! Lang::choice('messages.test-kit', 2) !!}</a>
        </li>
        <!-- User management -->
        <li>
            <a href="#"><i class="fa fa-users"></i> {!! Lang::choice('messages.user-management', 1) !!}<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{!! url('user') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.user', 2) !!}</a></li>
                <li><a href="{!! url('role') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.role', 2) !!}</a></li>
                <li><a href="{!! url('permission') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.permission', 2) !!}</a></li>
                <li><a href="{!! url('privilege') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.privilege', 2) !!}</a></li>
                <li><a href="{!! url('authorization') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.authorization', 2) !!}</a></li>
            </ul>
        </li>
         <!-- Data management -->
        <li>
            <a href="{!! url('review') !!}"><i class="fa fa-files-o fa-fw"></i> {!! Lang::choice('messages.data-management', 2) !!}</a>
        </li>
    </ul>
@show

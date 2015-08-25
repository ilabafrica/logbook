@section("sidebar")
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
        @if(Entrust::can('access-checklist-config'))
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
                <li><a href="{!! url('level') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.level', 2) !!}</a></li>
            </ul>
        </li>
        @endif
        @if(Entrust::can('access-facility-config'))
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
        @endif
         @if(Entrust::can('access-site-catalog'))
        <!-- Site catalog -->
        <li>
            <a href="#"><i class="fa fa-stack-exchange"></i> {!! Lang::choice('messages.site-catalog', 1) !!}<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{!! url('site') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.site', 2) !!}</a></li>
                <li><a href="{!! url('siteType') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.site-type', 2) !!}</a></li>
            </ul>
        </li>
        @endif
        <!-- Test kits -->
        @if(Entrust::can('access-testkits'))
        <li>
            <a href="{!! url('testKit') !!}"><i class="fa fa-book"></i> {!! Lang::choice('messages.test-kit', 2) !!}</a>
        </li>
         @endif
        
        @if(Entrust::can('access-users'))
        <!-- User management -->
        <li>
        <a href="{!! url('user') !!}"><i class="fa fa-users"></i> {{ Lang::choice('messages.user', 2) }}</a>
        </li>
    @endif
    @if(Entrust::can('access-access-controls'))
    <li>
        <a href="#"><i class="fa fa-database"></i> {{ Lang::choice('messages.access-controls', 1) }}<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
            <li><a href="{!! url('permission') !!}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.permission', 2) }}</a></li>
            <li><a href="{!! url('role') !!}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.role', 2) }}</a></li>
            <li><a href="{!! url('privilege') !!}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.privilege', 2) }}</a></li>
            <li><a href="{!! url('authorization') !!}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.authorization', 2) }}</a></li>
        </ul>
    </li>
    @endif
    @if(Entrust::can('access-data'))
        <!-- Data management -->
        <li>
            <a href="{!! url('review') !!}"><i class="fa fa-files-o fa-fw"></i> {!! Lang::choice('messages.data-management', 2) !!}</a>
        </li>
    @endif
    @if(Entrust::can('access-data-analysis'))
        <!-- Local partner analysis -->
        <li style="display:none;">
            <a href="#"><i class="fa fa-barcode"></i> {!! Lang::choice('messages.local-partner-analysis', 1) !!}<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{!! url('partner/accomplishment') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.accomplishment', 1) !!}</a></li>
                <li><a href="{!! url('partner/hr') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.hr', 1) !!}</a></li>
                <li><a href="{!! url('partner/pt') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.pt', 1) !!}</a></li>
                <li><a href="{!! url('partner/logbook') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.logbook', 1) !!}</a></li>
                <li><a href="{!! url('partner/spirt') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.spi-rt', 1) !!}</a></li>
                <li><a href="{!! url('analysis/chart') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.m-e', 1) !!}</a></li>
            </ul>
        </li>

        <!-- Summary and Data Analysis -->
        <li>
            <a href="#"><i class="fa fa-barcode"></i> {!! Lang::choice('messages.summary-analysis', 1) !!}<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{!! url('analysis/data') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.analysis', 2) !!}</a></li>
                <li><a href="{!! url('analysis/chart') !!}"><i class="fa fa-tag"></i> {!! Lang::choice('messages.summary-chart', 1) !!}</a></li>
            </ul>
        </li>
        @endif
    
    </ul>
@show

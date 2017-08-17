@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.report', 2) }}</li>
        </ol>
    </div>
</div>
@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-tags"></i> {!! $checklist->name !!}
        <span class="panel-btn">
            <a class="btn btn-info btn-sm" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li><a href="{!! url('report/'.$checklist->id.'/spirt') !!}">{!! Lang::choice('messages.summary-chart', 1) !!}</a></li>
            <li><a href="{!! url('partner/sdp') !!}">{!! Lang::choice('messages.level-comparison', 1) !!}</a></li>
            <li><a href="{!! url('partner/region') !!}">{!! Lang::choice('messages.geographic-location', 1) !!}</a></li>
            <li><a href="{!! url('partner/precert') !!}">{!! Lang::choice('messages.precert-summary', 1) !!}</a></li>
            <li><a href="{!! url('partner/overtime') !!}">{!! Lang::choice('messages.precert-overtime', 1) !!}</a></li>
            <li class="active"><a href="{!! url('partner/performance') !!}">{!! Lang::choice('messages.performance-overtime', 1) !!}</a></li>
        </ul>
        {!! Form::open(array('url' => 'partner/performance', 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) !!}
        <div class="container-fluid">
            <!-- Tab panes -->
            <div class="tab-content">
                <br />
                <div class="row">
                    @if(!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator')))
                    <div class="col-sm-4">
                        <div class='form-group'>
                            {!! Form::label(trans('messages.select-county'), trans('messages.select-county'), array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::select('county', array(''=>trans('messages.select-county'))+$counties, old($jimbo)?old($jimbo):$jimbo, 
                                    array('class' => 'form-control', 'id' => 'county', 'onchange' => "dyn()")) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(Auth::user()->hasRole('County Lab Coordinator') || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                    <div class="col-sm-4">
                        <div class='form-group'>
                            {!! Form::label(Lang::choice('messages.sub-county', 1), Lang::choice('messages.sub-county', 1), array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::select('sub_county', array(''=>trans('messages.select-sub-county'))+$subCounties, old($sub_county)?old($sub_county):$sub_county, 
                                    array('class' => 'form-control', 'id' => 'sub_county', 'onchange' => "drop()")) !!}
                            </div>
                        </div>
                    </div>

                @endif
                
            </div>
            <hr/>
            <div class="row">
                @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label(Lang::choice('messages.facility', 1), Lang::choice('messages.facility', 1), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('facility', array(''=>trans('messages.select-facility'))+$facilities, isset($site)?$site:'', 
                                array('class' => 'form-control', 'id' => 'facility', 'onchange' => "ssdp()")) !!}
                        </div>
                    </div>
                </div>
                @endif
                 @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label(Lang::choice('messages.sdp', 1), Lang::choice('messages.sdp', 1), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('sdp', array(''=>trans('messages.select-sdp'))+$sdps, isset($sdp)?$sdp:'', 
                                array('class' => 'form-control', 'id' => 'sdp')) !!}

                        </div>
                    </div>
                 </div>
                    @endif
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-4">
                        <div class='form-group'>
                            {!! Form::label('from', Lang::choice('messages.from', 1), array('class' => 'col-sm-4 control-label', 'style' => 'text-align:left')) !!}
                            <div class="col-sm-8 form-group input-group input-append date datepicker" style="padding-left:15px;">
                                {!! Form::text('from', isset($from)?$from:date('Y-m-01'), array('class' => 'form-control')) !!}
                                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class='form-group'>
                            {!! Form::label('to', Lang::choice('messages.to', 1), array('class' => 'col-sm-4 control-label', 'style' => 'text-align:left')) !!}
                            <div class="col-sm-8 form-group input-group input-append date datepicker" style="padding-left:15px;">
                                {!! Form::text('to', isset($to)?$to:date('Y-m-d'), array('class' => 'form-control')) !!}
                                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        {!! Form::button("<span class='glyphicon glyphicon-filter'></span> ".trans('messages.view'), 
                                    array('class' => 'btn btn-danger', 'name' => 'view', 'id' => 'view', 'type' => 'submit')) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <hr />
            <div class="row">                
                <div class="col-sm-12">
                    <div id="chart" style="height: 400px"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
<script src="{{ URL::asset('admin/js/highcharts.js') }}"></script>
<script src="{{ URL::asset('admin/js/highcharts-more.js') }}"></script>
<script src="{{ URL::asset('admin/js/exporting.js') }}"></script>
<script type="text/javascript">
    $(function () {
        $('#chart').highcharts(<?php echo $chart ?>);  
    });
</script>
@stop
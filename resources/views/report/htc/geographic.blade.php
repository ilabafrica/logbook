@extends("layout")
@section("content")
<br />
<div cla
="row">
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
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-tags"></i> {!! $checklist->name !!}
        <span class="panel-btn">
            <a class="btn btn-outline btn-primary btn-sm" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li><a href="{!! url('report/'.$checklist->id) !!}">{!! Lang::choice('messages.percent-positive', 1) !!}</a></li>
            <li><a href="{!! url('report/'.$checklist->id.'/agreement') !!}">{!! Lang::choice('messages.percent-positiveAgr', 1) !!}</a></li>
            <li><a href="{!! url('report/'.$checklist->id.'/overall') !!}">{!! Lang::choice('messages.percent-overallAgr', 1) !!}</a></li>
            <li><a href="{!! url('report/'.$checklist->id.'/programatic') !!}">{!! Lang::choice('messages.programatic-area', 1) !!}</a></li>
            <li class="active"><a href="{!! url('report/'.$checklist->id.'/geographic') !!}">{!! Lang::choice('messages.geographic-location', 1) !!}</a></li>
        </ul>
        <div class="container-fluid">
        {!! Form::open(array('url' => 'report/'.$checklist->id.'/geographic', 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) !!}
        <!-- Tab panes -->
        <div class="tab-content">
            <br />
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
                    <div class="input-group">
                        <div id="radioBtn" class="btn-group">
                            <a class="btn btn-primary btn {{($kit=='KHB')?'active':'notActive'}}" data-toggle="kit" data-title="KHB" name="kit">{!! Lang::choice('messages.khb', 1) !!}</a>
                            <a class="btn btn-primary btn {{($kit=='DETERMINE')?'active':'notActive'}}" data-toggle="kit" data-title="DETERMINE" name="kit">{!! Lang::choice('messages.determine', 1) !!}</a>
                        </div>
                        <input type="hidden" name="kit" id="kit">
                    </div>
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
<script src="{{ URL::asset('admin/js/drilldown.js') }}"></script>
<script type="text/javascript">
    $(function () {
        $('#chart').highcharts(<?php echo $chart ?>);
    });
</script>
@stop
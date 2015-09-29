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
            <li><a href="{!! url('report/'.$checklist->id.'/me') !!}">Chart Summary</a></li>
            <li><a href="{!! url('analysis/chart') !!}">Stage of Implementation</a></li>
            <li class="active"><a href="{!! url('analysis/snapshot') !!}">Snapshot</a></li>
        </ul>
        <div class="container-fluid"> 
        {!! Form::open(array('url' => 'analysis/snapshot', 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) !!}
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
                @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label(Lang::choice('messages.facility', 1), Lang::choice('messages.facility', 1), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('facility', array(''=>trans('messages.select-facility'))+$facilities, old($site)?old($site):$site, 
                                array('class' => 'form-control', 'id' => 'facility')) !!}
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
                            {!! Form::text('from', isset($from)?$from:date('Y-m-d'), array('class' => 'form-control')) !!}
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
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <td colspan="5"><strong>{!! Lang::choice('messages.snapshot-label', 1).$title !!}</strong></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    @foreach ($columns as $column)
                                        <td>{!! $column->label !!}</td>
                                    @endforeach
                                </tr>
                                @foreach($options as $option)
                                <tr>
                                    <td>{!! $option.' ('.App\Models\Answer::find(App\Models\Answer::idByName($option))->range_lower.'%-'.App\Models\Answer::find(App\Models\Answer::idByName($option))->range_upper.'%)' !!}</td>
                                    @foreach ($columns as $column)
                                        @if($column->snapshot($jimbo, $sub_county, $site, $from, $toPlusOne)>=App\Models\Answer::find(App\Models\Answer::idByName($option))->range_lower && $column->snapshot($jimbo, $sub_county, $site, $from, $toPlusOne) < App\Models\Answer::find(App\Models\Answer::idByName($option))->range_upper)
                                            <td class="{{ $column->color($column->snapshot($jimbo, $sub_county, $site, $from, $toPlusOne)) }}">{!! $column->snapshot($jimbo, $sub_county, $site, $from, $toPlusOne) !!}</td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div id="chart" style="height: 400px"></div>
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
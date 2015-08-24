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
        {!! $checklist->name !!}
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="{!! url('analysis/chart') !!}">Stage of Implementation</a></li>
            <li><a href="{!! url('analysis/snapshot') !!}">Snapshot</a></li>
        </ul>
        {!! Form::open(array('url' => 'report/'.$checklist->id, 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) !!}
        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            <div class="row">
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label('from', Lang::choice('messages.from', 1), array('class' => 'col-sm-4 control-label', 'style' => 'text-align:left')) !!}
                        <div class="col-sm-8 form-group input-group input-append date datepicker" style="padding-left:15px;">
                            {!! Form::text('from', old('from'), array('class' => 'form-control')) !!}
                            <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label('to', Lang::choice('messages.to', 1), array('class' => 'col-sm-4 control-label', 'style' => 'text-align:left')) !!}
                        <div class="col-sm-8 form-group input-group input-append date datepicker" style="padding-left:15px;">
                            {!! Form::text('to', old('from'), array('class' => 'form-control')) !!}
                            <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::button("<span class='glyphicon glyphicon-filter'></span> ".trans('messages.view'), 
                                array('class' => 'btn btn-danger', 'name' => 'view', 'id' => 'view', 'type' => 'submit')) !!}
                </div>
                <hr >
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <td colspan="5"><strong>{!! Lang::choice('messages.current-implementing-stage', 1) !!}</strong></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    @foreach ($columns as $column)
                                            <td><strong>{!! $column->label !!}</strong></td>
                                    @endforeach
                                </tr>
                                @foreach ($options as $option)
                                    <?php
                                        $class = '';
                                        $answer = $option;
                                        if($answer == 'Does Not Exist')
                                            $class = 'does-not-exist';
                                        else if($answer == 'In Development')
                                            $class = 'in-development';
                                        else if($answer == 'Being Implemented')
                                            $class = 'being-implemented';
                                        else if($answer == 'Completed')
                                            $class = 'completed';
                                    ?>
                                    <tr>
                                        <td class="{{$class}}">{!! $answer !!} ({!! App\Models\Answer::find(App\Models\Answer::idByName($answer))->score !!})</td>
                                        @foreach($columns as $column)
                                            <td>{!! $column->column()!=0?round(App\Models\Answer::find(App\Models\Answer::idByName($option))->column($column->id)*100/$column->column(), 2).'%':0.00; !!}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div id="chart" style="height: 400px"></div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
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
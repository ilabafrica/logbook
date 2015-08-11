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
            <li><a href="{!! url('partner/logbook') !!}">{!! Lang::choice('messages.period', 1) !!}</a></li>
            <li><a href="{!! url('partner/logSdp') !!}">{!! Lang::choice('messages.sdp', 1) !!}</a></li>
            <li class="active"><a href="{!! url('partner/logRegion') !!}">{!! Lang::choice('messages.region', 1) !!}</a></li>
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
                                    <td colspan="{{ count($regions)+1 }}">{!! Lang::choice('messages.percent-of-sites', 1) !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! Lang::choice('messages.agreement-rate', 1) !!}</td>
                                    @foreach($regions as $region)
                                        <td>{!! App\Models\County::find($region)->name !!}</td>
                                    @endforeach
                                </tr>
                                @foreach($rates as $rate)
                                <tr>
                                    <td>{!! $rate !!}</td>
                                    @foreach($regions as $region)
                                        <td></td>
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
</script>
@stop
@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.summary', 1) }}</li>
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
            <li><a href="{!! url('survey/'.$checklist->id.'/collection') !!}">{!! Lang::choice('messages.data-collection-summary', 1) !!}</a></li>
            @if(!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator')))
                <li class="active"><a href="{!! url('survey/'.$checklist->id.'/county') !!}">{!! Lang::choice('messages.county-summary', 1) !!}</a></li>
            @endif
            @if(Auth::user()->hasRole('County Lab Coordinator') || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <li class=""><a href="{!! url('survey/'.$checklist->id.'/subcounty') !!}">{!! Lang::choice('messages.sub-county-summary', 1) !!}</a></li>
            @endif
            @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <li><a href="{!! url('survey/'.$checklist->id.'/participant') !!}">{!! Lang::choice('messages.participants', 1) !!}</a></li>
            @endif
            @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <li class=""><a href="{!! url('survey/'.$checklist->id.'/sdp') !!}">{!! Lang::choice('messages.sdp', 1) !!}</a></li>
            @endif
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            {!! Form::open(array('url' => 'survey/'.$checklist->id.'/county', 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) !!}
            <!-- Tab panes -->
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
                <div class="col-sm-1">
                    {!! Form::button("<span class='glyphicon glyphicon-filter'></span> ".trans('messages.view'), 
                                array('class' => 'btn btn-danger', 'name' => 'view', 'id' => 'view', 'type' => 'submit')) !!}
                </div>
                <div class="col-sm-1">
                    {!! Form::button("<i class='fa fa-download'></i> ".Lang::choice('messages.download-summary', 1), 
                                array('class' => 'btn btn-success', 'name' => 'download', 'id' => 'download', 'value' => 'download', 'type' => 'submit')) !!}
                </div>
            </div>
            {!! Form::close() !!}
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover search-table">
                        <thead>
                            <tr>
                                <th>{{ Lang::choice('messages.county', 1) }}</th>
                                <th>{{ Lang::choice('messages.number', 1) }}</th>
                            </tr>
                        </thead>
                         <tbody>
                            @foreach($counties as $key => $value)
                            <tr>
                                <td>{!! $value !!}</td>
                                <td>{!! $checklist->fsdps($checklist->id, $key, NULL, NULL, NULL, $from, $toPlusOne)->count() !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@stop
@extends("layout")
@section("content")
<style type="text/css">
    .datepicker{
        z-index: 1051 !important;
    }
</style>
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.survey', 1) }}</li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.survey', 2) }}</div>
    <!-- .panel-heading -->
    <div class="panel-body">
        @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">{{ Lang::choice('messages.close', 1) }}</span></button>
          {!! session('message') !!}
        </div>
        @endif
        <div class="panel-group" id="accordion">
            @foreach($checklists as $checklist)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <strong><a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$checklist->id}}">{!! $checklist->name !!}</a></strong>
                    </h5>
                </div>
                <div id="collapse{{$checklist->id}}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td>{!! Lang::choice('messages.no-of-questionnaire', 1) !!}</td>
                                        @if($county || $subCounty)
                                            <td>{!! count($surveys[$checklist->id]) !!}</td>
                                        @else
                                            <td>{!! $checklist->surveys->count() !!}</td>
                                        @endif                                        
                                    </tr>
                                    <tr>
                                        <td>{!! Lang::choice('messages.no-of-qa', 1) !!}</td>
                                        <td>{!! $checklist->officers($county, $subCounty) !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p>
                                @if(Entrust::can('edit-checklist-data'))
                                    <a href="{!! url('/survey/'.$checklist->id.'/create') !!}" class="btn btn-info"><i class="glyphicon glyphicon-pencil"></i><span> {!! Lang::choice('messages.fill-questionnaire', 1) !!}</span></a>
                                @endif
                                <a href="{!! url('/survey/'.$checklist->id.'/list') !!}" class="btn btn-default"><i class="fa fa-book"></i><span> {!! Lang::choice('messages.view-collected-data', 1) !!}</span></a>
                                <a href="{!! url('/survey/'.$checklist->id.'/collection') !!}" class="btn btn-success"><i class="fa fa-database"></i><span> {!! Lang::choice('messages.view-summary', 1) !!}</span></a>
                                @if($checklist->id == App\Models\Checklist::idByName('M & E Checklist'))
                                    <a href="{!! url('/report/'.$checklist->id.'/me') !!}" class="btn btn-warning"><i class="fa fa-bar-chart-o"></i><span> {!! Lang::choice('messages.view-report', 1) !!}</span></a>
                                @elseif($checklist->id == App\Models\Checklist::idByName('SPI-RT Checklist'))
                                    <a href="{!! url('/report/'.$checklist->id.'/spirt') !!}" class="btn btn-warning"><i class="fa fa-bar-chart-o"></i><span> {!! Lang::choice('messages.view-report', 1) !!}</span></a>
                                @else
                                    <a href="{!! url('/report/'.$checklist->id) !!}" class="btn btn-warning"><i class="fa fa-bar-chart-o"></i><span> {!! Lang::choice('messages.view-report', 1) !!}</span></a>
                                @endif
                                @if(Entrust::can('edit-checklist-data'))
                                    <a href="{!! url('/api/'.$checklist->id) !!}" class="btn btn-danger"><i class="fa fa-download"></i><span> {!! Lang::choice('messages.import-submitted-data', 1) !!}</span></a>
                                    <!-- <button class="btn btn-danger import-data-item-link" data-toggle="modal" data-target=".import-data-modal" data-checklist="{{{ $checklist->name }}}" data-id="{!! $checklist->id !!}" class="btn btn-danger"><i class="fa fa-download"></i><span> {!! Lang::choice('messages.import-submitted-data', 1) !!}</span></button> -->
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- .panel-body -->
</div>
<!-- Duplicate Modal-->
<div class="modal fade import-data-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        {!! Form::open(array('route' => 'survey.import', 'id' => 'form-import-data', 'class' => 'form-inline', 'method' => 'POST')) !!}
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- checklist-id -->
            <input type="hidden" id="checklist_id" name="checklist_id" value="" />
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    <i class="fa fa-download"></i><span> 
                    {!! trans('messages.confirm-data-import').' for <strong id="checklist"></strong>' !!}
                    </span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class='form-group'>
                            {!! Form::label('from', Lang::choice('messages.from', 1)) !!}
                            <div class="col-sm-9 form-group input-group input-append date datepicker limits">
                                {!! Form::text('from', '', array('class' => 'form-control', 'id' => 'fromImport')) !!}
                                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='form-group'>
                            {!! Form::label('to', Lang::choice('messages.to', 1)) !!}
                            <div class="col-sm-9 form-group input-group input-append date datepicker limits">
                                {!! Form::text('to', '', array('class' => 'form-control', 'id' => 'toImport')) !!}
                                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-import" onclick="submit()" disabled>
                    <i class="fa fa-download"></i><span> {{ trans('messages.import-submitted-data') }}</span>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times-circle-o"></i><span> {{ trans('messages.cancel') }}</span>
                </button>
            </div>
        {!! Form::close() !!}
        </div>
    </div>
</div>
{!! session(['SOURCE_URL' => URL::full()]) !!}
@stop
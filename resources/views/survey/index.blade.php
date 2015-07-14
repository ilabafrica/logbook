@extends("layout")
@section("content")
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
                                        <td>{!! $checklist->surveys->count() !!}</td>                                        
                                    </tr>
                                    <tr>
                                        <td>{!! Lang::choice('messages.no-of-qa', 1) !!}</td>
                                        <td>3</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p>
                                <a href="{!! url('/survey/'.$checklist->id.'/create') !!}" class="btn btn-info"><i class="glyphicon glyphicon-pencil"></i><span> {!! Lang::choice('messages.fill-questionnaire', 1) !!}</span></a>
                                <a href="{!! url('/survey/'.$checklist->id.'/list') !!}" class="btn btn-default"><i class="fa fa-book"></i><span> {!! Lang::choice('messages.view-collected-data', 1) !!}</span></a>
                                <a href="{!! url('/survey/'.$checklist->id.'/summary') !!}" class="btn btn-success"><i class="fa fa-database"></i><span> {!! Lang::choice('messages.view-summary', 1) !!}</span></a>
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
@stop
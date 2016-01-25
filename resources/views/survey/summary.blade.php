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
<div class="panel panel-primary">
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
            <li><a href="{!! url('survey/'.$checklist->id.'/collection') !!}">{!! Lang::choice('messages.data-collection-summary', 1) !!}</a>
            </li>
            <li class="active"><a href="{!! url('survey/'.$checklist->id.'/summary') !!}">{!! Lang::choice('messages.survey-summary', 1) !!}</a>
            </li>
            <li><a href="{!! url('survey/'.$checklist->id.'/participant') !!}">{!! Lang::choice('messages.participants', 1) !!}</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            <p>
                <a href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}"><i class="fa fa-chevron-left"></i> {!! Lang::choice('messages.back', 1) !!}</a>
                <a href="#" class="btn btn-success" target=""><i class="fa fa-download"></i> {!! Lang::choice('messages.download-summary', 1) !!}</a>
            </p>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{{ Lang::choice('messages.question', 1) }}</th>
                                <th>{{ Lang::choice('messages.response', 2) }}</th>
                                <th>{{ Lang::choice('messages.number', 1) }}</th>
                            </tr>
                        </thead>
                         <tbody>
                         @foreach($sections as $section)
                            <tr>
                                <td colspan="3" class="text-muted"><strong>{!! $section->name !!}</strong></td>
                            </tr>
                            @foreach($section->questions as $question)
                                @if($question->question_type == App\Models\Question::CHOICE)
                                <tr>
                                    <td>{!! $question->name !!}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                    @foreach($question->answers as $answer)
                                    <tr>
                                        <td></td>
                                        <td>{!! $answer->name !!}</td>
                                        <td>{!! $question->responses($answer->name) !!}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            @endforeach
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
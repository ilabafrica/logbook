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
    <i class="fa fa-tags"></i> {!! $surveysdp->survey->checklist->name !!}
        <span class="panel-btn">
            <a class="btn btn-outline btn-primary btn-sm" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">

        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            @if(!$surveysdp->survey->checklist->id == App\Models\Checklist::idByName('HTC Lab Register (MOH 362)'))
            <p>
                <a href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}"><i class="fa fa-chevron-left"></i> {!! Lang::choice('messages.back', 1) !!}</a>
                <a href="{!! url('surveysdp/'.$surveysdp->id.'/download') !!}" class="btn btn-success" target=""><i class="fa fa-download"></i> {!! Lang::choice('messages.download-summary', 1) !!}</a>
            </p>
            @endif
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{{ Lang::choice('messages.sdp', 1) }}</th>
                                <th>{{ Lang::choice('messages.description', 1) }}</th>
                                @if($surveysdp->survey->checklist->id == App\Models\Checklist::idByName('HTC Lab Register (MOH 362)'))
                                    <th>{{ Lang::choice('messages.page-no', 1) }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $surveysdp->sdp->name !!}</td>
                                <td>{!! $surveysdp->comment !!}</td>
                                @if($surveysdp->survey->checklist->id == App\Models\Checklist::idByName('HTC Lab Register (MOH 362)'))
                                    <td>{!! $surveysdp->pages->count() !!}</td>
                                @endif
                            </tr>
                        </tbody>
                     </table>
                </div>
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                    @if($surveysdp->survey->checklist->id == App\Models\Checklist::idByName('HTC Lab Register (MOH 362)'))
                        <thead>
                            <tr>
                                <th>{{ Lang::choice('messages.page-no', 1) }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <?php $counter = 0; ?>
                        <tbody>
                            @foreach($surveysdp->pages as $page)
                            <?php $counter++; ?>
                            <tr>
                                <td>{!! $counter !!}</td>
                                <td>
                                    <a href="{!! url('page/'.$page->id) !!}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> {{ Lang::choice('messages.view', 1) }}</span></a>
                                    <a href="{!! url('page/'.$page->id.'/edit') !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> {{ Lang::choice('messages.edit', 1) }}</span></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    @else
                        <thead>
                            <tr>
                                <th>{{ Lang::choice('messages.question', 1) }}</th>
                                <th>{{ Lang::choice('messages.response', 1) }}</th>
                            </tr>
                        </thead>
                        <?php $counter = 0; ?>
                        <tbody>
                            @foreach($surveysdp->sqs as $sq)
                            <?php $qstn = App\Models\Question::find($sq->question_id); ?>
                            <tr>
                                <td>{!! $qstn->name !!}</td>
                                <td>{!! $qstn->question_type == App\Models\Question::CHOICE?App\Models\Answer::nameByScore($surveysdp->survey->checklist->id == App\Models\Checklist::idByName('M & E Checklist')?'nthenya':NULL, $sq->sd->answer):$sq->sd->answer !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    @endif
                     </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@stop
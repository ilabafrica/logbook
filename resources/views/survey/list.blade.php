@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
             <li class="active">
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.collected-data', 1) }} 
    @if(Entrust::can('edit-checklist-data'))
        <span class="panel-btn">
            <a class="btn btn-sm btn-info" href="{{ URL::to("survey/".$checklist->id."/create") }}" >
                <span class="glyphicon glyphicon-plus-sign"></span>
                {{ trans('messages.fill-questionnaire') }}
            </a>
        </span>
    @endif
        <span class="panel-btn">
            <a class="btn btn-sm btn-info" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <div class="panel-body">
        @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">{{ Lang::choice('messages.close', 1) }}</span></button>
          {!! session('message') !!}
        </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.response-no', 1) }}</th>
                            <th>{{ Lang::choice('messages.qa-officer', 1) }}</th>
                            <th>{{ Lang::choice('messages.facility', 1) }}</th>
                            <th>{{ Lang::choice('messages.sdp', 2) }}</th>
                            <th>{{ Lang::choice('messages.date', 1) }}</th>
                            <th>{{ Lang::choice('messages.status', 1) }}</th>
                            <th>{{ Lang::choice('messages.action', 2) }}</th>
                        </tr>
                    </thead>
                     <tbody>
                        <?php $counter = 0; ?>
                        @forelse($surveys as $survey)
                        <?php $counter++; ?>
                        <tr>
                            <td>{{ $counter }}</td>
                            <td>{{ $survey->qa_officer }}</td>
                            <td>{{ $survey->facility->name }}</td>
                            <td>{{ implode(", ", $survey->ssdps()) }}</td>
                            <td>{{ $survey->created_at }}</td>
                            <td></td>
                            <td>
                                <a href="{!! url('survey/'.$survey->id) !!}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> {!! Lang::choice('messages.view', 1) !!}</span></a>
                                @if(Entrust::can('edit-checklist-data'))
                                    <a href="{!! url('survey/'.$survey->id.'/edit') !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> {!! Lang::choice('messages.edit', 1) !!}</span></a>
                                    @if($survey->checklist->name == 'HTC Lab Register (MOH 362)')
                                        <button class="btn btn-warning btn-sm data-month-item-link" data-toggle="modal" data-target=".data-month-modal" data-id="{{{ $survey->id }}}" data-facility="{{{ $survey->facility->name }}}" data-submitted="{{{ $survey->date_submitted }}}" data-officer="{{{ $survey->qa_officer }}}" data-sdps="{{{ implode(", ", $survey->ssdps()) }}}"  data-oldest="{{{ json_decode($survey->dates())->min }}}" data-newest="{{{ json_decode($survey->dates())->max }}}"><i class="fa fa-lemon-o"></i><span> {!! Lang::choice('messages.data-month', 1) !!}</span></button>
                                    @endif
                                    <button class="btn btn-danger btn-sm delete-item-link" data-toggle="modal" data-target=".confirm-delete-modal" data-id="{!! url('survey/'.$survey->id.'/delete') !!}"><i class="fa fa-trash-o"></i><span> {!! Lang::choice('messages.delete', 1) !!}</span></button>                              
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="4">{{ Lang::choice('messages.no-records-found', 1) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Duplicate Modal-->
<div class="modal fade data-month-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        {!! Form::open(array('route' => 'survey.sdp.data', 'id' => 'form-data-survey-sdp-data', 'class' => 'form-horizontal', 'method' => 'POST')) !!}
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- survey-sdp-id -->
            <input type="hidden" id="dataMonth" name="dataMonth" value="" />
            <!-- newest date -->
            <input type="hidden" id="newest_date" name="newest_date" value="" />
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    <i class="fa fa-lemon-o"></i><span> 
                    {{ trans('messages.confirm-data-month') }}
                    </span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <th>{!! Lang::choice('messages.facility', 1) !!}</th>
                                    <td colspan="2"><div id="facility"></div></td>
                                </tr>
                                <tr>
                                    <th>{!! Lang::choice('messages.qa-officer', 1) !!}</th>
                                    <td colspan="2"><div id="officer"></div></td>
                                </tr>
                                <tr>
                                    <th>{!! Lang::choice('messages.date-submitted', 1) !!}</th>
                                    <td colspan="2"><div id="date_submitted"></div></td>
                                </tr>
                                <tr>
                                    <th>{!! Lang::choice('messages.sdp', 2) !!}</th>
                                    <td colspan="2"><div id="sdp"></div></td>
                                </tr>
                                <tr>
                                    <th rowspan="2">{!! Lang::choice('messages.page-register-start-date', 1) !!}</th>
                                    <td>{!! Lang::choice('messages.oldest', 1) !!}</td>
                                    <td><div id="oldest"></div></td>
                                </tr>
                                <tr>
                                    <td>{!! Lang::choice('messages.newest', 1) !!}</td>
                                    <td><div id="newest"></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-duplicate" onclick="submit()">
                    <i class="fa fa-lemon-o"></i><span> {{ trans('messages.data-month') }}</span>
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
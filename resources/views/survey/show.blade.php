@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li>
                <a href="{{ url('survey') }}">{{ Lang::choice('messages.survey', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.view', 1) }}</li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
  <div class="panel-heading"><i class="fa fa-tags"></i> {!! $survey->checklist->name !!}
  @if(Entrust::can('edit-checklist-data'))
    <span class="panel-btn">
        <a class="btn btn-sm btn-info" href="{!! url('survey/'.$survey->id.'/edit') !!}" >
            <i class="fa fa-edit"></i><span> {{ trans('messages.edit-questionnaire') }}</span>
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
      @if(session()->has('warning'))
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">{{ Lang::choice('messages.close', 1) }}</span></button>
        {!! session('warning') !!}
      </div>
      @endif
      <table class="table table-striped table-bordered table-hover">
          <thead>
              <tr>
                  <th>{{ Lang::choice('messages.question', 1) }}</th>
                  <th>{{ Lang::choice('messages.response', 1) }}</th>
              </tr>
          </thead>
          <tbody>
                  <tr>
                      <td>{{ Lang::choice('messages.checklist', 1) }}</td>
                      <td>{!! $survey->checklist->name !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.start-time', 1) }}</td>
                      <td>{!! $survey->date_started !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.qa-officer', 1) }}</td>
                      <td>{!! $survey->qa_officer !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.county', 1) }}</td>
                      <td>{!! $survey->facility->subCounty->county->name !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.sub-county', 1) }}</td>
                      <td>{!! $survey->facility->subCounty->name !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.facility', 1) }}</td>
                      <td>{!! $survey->facility->name !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.gps', 1) }}</td>
                      <td>{!! $survey->latitude.' '.$survey->longitude !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.comment', 1) }}</td>
                      <td>{!! $survey->comment !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.end-time', 1) }}</td>
                      <td>{!! $survey->date_ended !!}</td>
                  </tr>
                  <tr>
                      <td>{{ Lang::choice('messages.submit-time', 1) }}</td>
                      <td>{!! $survey->date_submitted !!}</td>
                  </tr>
          </tbody>
      </table>
      <table class="table table-striped table-bordered table-hover">
          <thead>
              <tr>
                  <th>{{ Lang::choice('messages.sdp', 1) }}</th>
                  <th>{{ Lang::choice('messages.description', 1) }}</th>
                  @if($survey->checklist->id == App\Models\Checklist::idByName('HTC Lab Register (MOH 362)'))
                      <th>{{ Lang::choice('messages.page-no', 1) }}</th>
                  @endif
                  <th></th>
              </tr>
          </thead>
          <tbody>
              @foreach($survey->sdps as $sdp)
              <tr>
                  <td>{!! $sdp->sdp->name !!}</td>
                  <td>{!! $sdp->comment !!}</td>
                  @if($survey->checklist->id == App\Models\Checklist::idByName('HTC Lab Register (MOH 362)'))
                      <td>{!! $sdp->pages->count() !!}</td>
                  @endif
                  <td>
                      <a href="{!! url('surveysdp/'.$sdp->id) !!}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> {{ Lang::choice('messages.view', 1) }}</span></a>
                      @if(Entrust::can('edit-checklist-data'))
                          @if($survey->checklist->id != App\Models\Checklist::idByName('HTC Lab Register (MOH 362)'))
                              <a href="{!! url('surveysdp/'.$sdp->id.'/edit') !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> {{ Lang::choice('messages.edit', 1) }}</span></a>
                          @else
                              <button class="btn btn-info btn-sm  edit-item-link" data-toggle="modal" data-target=".confirm-edit-modal" data-id="{{{ $sdp->id }}}" data-contents="{{{ $sdp->sdp->name.' - '.$sdp->comment }}}"><i class="fa fa-edit"></i><span> {{ Lang::choice('messages.edit', 1) }}</span></button>
                          @endif
                          <button class="btn btn-warning btn-sm duplicate-item-link" data-toggle="modal" data-target=".confirm-duplicate-modal" data-id="{{{ $sdp->id }}}" data-contents="{{{ $sdp->sdp->name.' - '.$sdp->comment }}}"><i class="fa fa-files-o"></i><span> {!! Lang::choice('messages.duplicate', 1) !!}</span></button>
                          <button class="btn btn-danger btn-sm delete-item-link" data-toggle="modal" data-target=".confirm-delete-modal" data-id="{{{ url('surveysdp/'.$sdp->id.'/delete') }}}"><i class="fa fa-trash-o"></i><span> {!! Lang::choice('messages.delete', 1) !!}</span></button>
                      @endif
                  </td>
              </tr>
              @endforeach
          </tbody>
      </table>
  </div>
</div>
<!-- Duplicate Modal-->
<div class="modal fade confirm-duplicate-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        {!! Form::open(array('route' => 'survey.sdp.duplicate', 'id' => 'form-duplicate-survey-sdp-data', 'class' => 'form-horizontal', 'method' => 'POST')) !!}
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- survey-sdp-id -->
            <input type="hidden" id="ssdpForDuplicate" name="ssdpForDuplicate" value="" />
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    <i class="fa fa-files-o"></i><span> 
                    {{ trans('messages.confirm-duplicate-title') }}
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
                                    <td>{!! $survey->facility->name !!}</td>
                                </tr>
                                <tr>
                                    <th>{!! Lang::choice('messages.qa-officer', 1) !!}</th>
                                    <td>{!! $survey->qa_officer !!}</td>
                                </tr>
                                <tr>
                                    <th>{!! Lang::choice('messages.sdp', 1) !!}</th>
                                    <td><div id="to-be-duplicated"></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class='form-group'>
                            {!! Form::label(Lang::choice('messages.select-sdp', 1), Lang::choice('messages.select-sdp', 1), array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::select('sdp', array(''=>trans('messages.select-sdp'))+$sdps, '', 
                                    array('class' => 'form-control', 'id' => 'sdp')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('desription', Lang::choice('messages.description', 1), array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('description', old(''), array('class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-duplicate" onclick="submit()" disabled>
                    <i class="fa fa-files-o"></i><span> {{ trans('messages.duplicate') }}</span>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times-circle-o"></i><span> {{ trans('messages.cancel') }}</span>
                </button>
            </div>
        {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Edit Modal-->
<div class="modal fade confirm-edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        {!! Form::open(array('route' => 'survey.sdp.modal.edit', 'id' => 'form-duplicate-survey-sdp-data', 'class' => 'form-horizontal', 'method' => 'POST')) !!}
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- survey-sdp-id -->
            <input type="hidden" id="ssdpForUpdate" name="ssdpForUpdate" value="" />
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    <i class="fa fa-edit"></i><span> 
                    {{ trans('messages.confirm-update-title') }}
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
                                    <td>{!! $survey->facility->name !!}</td>
                                </tr>
                                <tr>
                                    <th>{!! Lang::choice('messages.qa-officer', 1) !!}</th>
                                    <td>{!! $survey->qa_officer !!}</td>
                                </tr>
                                <tr>
                                    <th>{!! Lang::choice('messages.sdp', 1) !!}</th>
                                    <td><div id="to-be-updated"></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class='form-group'>
                            {!! Form::label(Lang::choice('messages.select-sdp', 1), Lang::choice('messages.select-sdp', 1), array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::select('sdp', array(''=>trans('messages.select-sdp'))+$sdps, '', 
                                    array('class' => 'form-control', 'id' => 'ssdp')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('comm', Lang::choice('messages.description', 1), array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('comm', old(''), array('class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-update" onclick="submit()" disabled>
                    <i class="fa fa-edit"></i><span> {{ Lang::choice('messages.save', 1) }}</span>
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
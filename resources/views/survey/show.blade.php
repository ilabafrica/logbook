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
  <div class="panel-heading"><i class="fa fa-tags"></i> {!! $survey->checklist->name !!} <span class="panel-btn">
  <a class="btn btn-sm btn-info" href="{!! url('survey/'.$survey->id.'/edit') !!}" >
    <i class="fa fa-edit"></i><span> {{ trans('messages.edit-questionnaire') }}</span>
  </a>
  </span></div>
  <div class="panel-body">
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
                      <a href="{!! url('surveysdp/'.$sdp->id.'/edit') !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> {{ Lang::choice('messages.edit', 1) }}</span></a>
                  </td>
              </tr>
              @endforeach
          </tbody>
      </table>
  </div>
</div>
@stop
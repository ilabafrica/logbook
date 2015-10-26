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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.collected-data', 1) }} <span class="panel-btn">
      <a class="btn btn-sm btn-info" href="{{ URL::to("survey/".$checklist->id."/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.fill-questionnaire') }}
          </a>
        </span>
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
                              <a href="{!! url('survey/'.$survey->id.'/edit') !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> {!! Lang::choice('messages.edit', 1) !!}</span></a>
                              <button class="btn btn-danger btn-sm delete-item-link" data-toggle="modal" data-target=".confirm-delete-modal" data-id="{!! url('survey/'.$survey->id.'/delete') !!}"><i class="fa fa-trash-o"></i><span> {!! Lang::choice('messages.delete', 1) !!}</span></button>
                              
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
            {!! session(['SOURCE_URL' => URL::full()]) !!}
        </div>
    </div>
</div>
@stop
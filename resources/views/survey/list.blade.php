@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <a href="#"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
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
                            <th>{{ Lang::choice('messages.date', 1) }}</th>
                            <th>{{ Lang::choice('messages.status', 1) }}</th>
                            <th>{{ Lang::choice('messages.action', 2) }}</th>
                        </tr>
                    </thead>
                     <tbody>
                        <?php $counter = 0; ?>
                        @forelse($checklist->surveys as $survey)
                        <?php $counter++; ?>
                        <tr>
                            <td>{{ $counter }}</td>
                            <td>{{ $survey->qa_officer }}</td>
                            <td>{{ $survey->created_at }}</td>
                            <td></td>
                            <td>
                                
                              <a href="{!! url('survey/'.$survey->id."/". $checklist_id) !!}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                              <a href="{!! url('survey/'.$survey->id."/". $checklist_id. "/edit") !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                              <a href="" class="btn btn-danger btn-sm"><i class="fa fa-edit"></i><span> Mark as Reviewed</span></a>
                              <a href="#" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>
                              
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
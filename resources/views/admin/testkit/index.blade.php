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
@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.test-kit', 2) }} <span class="panel-btn">
      <a class="btn btn-sm btn-info" href="{{ URL::to("testkit/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ Lang::choice('messages.create-test-kit', 1) }}
          </a>
        </span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.test-kit-name', 1) }}</th>
                            <th>{{ Lang::choice('messages.kit-name', 1) }}</th>
                             <th>{{ Lang::choice('messages.manufacturer', 1) }}</th>
                            <th>{{ Lang::choice('messages.approval-status', 1) }}</th>
                             <th>{{ Lang::choice('messages.approval-agency', 1) }}</th>
                            <th>{{ Lang::choice('messages.incountry-approval', 1) }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testkits as $testkit)
                        <tr>
                            <td>{{ $testkit->full_testkit_name }}</td>
                            <td>{{ $testkit->kit_name }}</td>
                            <td>{{ $testkit->manufacturer }}</td>
                            <td>{{ $testkit->approval_status }}</td>
                            <td>{{ $testkit->approval_agency_id }}</td>
                            <td>{{ $testkit->incountry_approval }}</td>
                            <td>
                              <a href="{{ URL::to("testkit/" . $testkit->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                              <a href="{{ URL::to("testkit/" . $testkit->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                              <a href="{{ URL::to("testkit/" . $testkit->id . "/delete") }}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>
                             
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="3"><th>{{ Lang::choice('messages.no-records-found', 1) }}</th></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
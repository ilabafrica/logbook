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
  <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.test-kit', 1) }} <span class="panel-btn">
  <a class="btn btn-sm btn-info" href="{{ URL::to("testKit/" . $testkit->id . "/edit") }}" >
    <i class="fa fa-edit"></i><span>{{ Lang::choice('messages.edit-test-kit', 1) }}</span>
  </a>
  </span></div>
  <div class="panel-body">
    <div class="panel panel-default">
      <div class="panel-body">
        <h4 class="no-margn view">
          <strong>{{ Lang::choice('messages.test-kit-name', 1) }}:</strong> <span> {{ $testkit->full_name }}</span>
        </h4>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.kit-name', 1) }}:</strong> <span> {{ $testkit->short_name }}</span>
        </h5>
         <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.manufacturer', 1) }}:</strong> <span> {{ $testkit->manufacturer }}</span>
        </h5>
         <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.approval-status', 1) }}:</strong> <span> {{ $testkit->approval_status }}</span>
        </h5>
         <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.approval-agency', 1) }}:</strong> <span> {{ $testkit->approval_agency_id }}</span>
        </h5>
         <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.incountry-approval', 1) }}:</strong> <span> {{ $testkit->incountry_approval }}</span>
        </h5>
      </div>
  </div>
</div>
</div>
@stop
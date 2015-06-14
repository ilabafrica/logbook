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
  <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.site', 1) }} <span class="panel-btn">
  <a class="btn btn-sm btn-info" href="{{ URL::to("site/" . $site->id . "/edit") }}" >
    <i class="fa fa-edit"></i><span>{{ Lang::choice('messages.edit-site', 1) }}</span>
  </a>
  </span></div>
  <div class="panel-body">
    <div class="panel panel-default">
      <div class="panel-body">
        <h4 class="no-margn view">
          <strong>{{ Lang::choice('messages.site-name', 1) }}:</strong> <span> {{ $site->code }}</span>
        </h4>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.site-type', 1) }}:</strong> <span> {{ $site->site_type_id }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.county', 1) }}:</strong> <span> {{ $site->county_id}}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.department', 1) }}:</strong> <span> {{ $site->department }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.reporting-to-facility', 1) }}:</strong> <span> {{ $site->facility_id }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.in-charge', 1) }}:</strong> <span> {{ $site->in_charge }}</span>
        </h5>
        <hr>
         <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.mobile', 1) }}:</strong> <span> {{ $site->mobile }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.email', 1) }}:</strong> <span> {{ $site->email }}</span>
        </h5>
        </div>
  </div>
</div>
</div>
@stop
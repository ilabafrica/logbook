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
  <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.facility', 1) }} <span class="panel-btn">
  <a class="btn btn-sm btn-info" href="{{ URL::to("facility/" . $facility->id . "/edit") }}" >
    <i class="fa fa-edit"></i><span>{{ Lang::choice('messages.edit-facility', 1) }}</span>
  </a>
  </span></div>
  <div class="panel-body">
    <div class="panel panel-default">
      <div class="panel-body">
        <h4 class="no-margn view">
          <strong>{{ Lang::choice('messages.code', 1) }}:</strong> <span> {{ $facility->code }}</span>
        </h4>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.name', 1) }}:</strong> <span> {{ $facility->name }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.facility-type', 1) }}:</strong> <span> {{ $facility->facilityType->name}}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.facility-owner', 1) }}:</strong> <span> {{ $facility->facilityOwner->name }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.nearest-town', 1) }}:</strong> <span> {{ $facility->nearest_town }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.landline', 1) }}:</strong> <span> {{ $facility->landline }}</span>
        </h5>
        <hr>
         <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.mobile', 1) }}:</strong> <span> {{ $facility->mobile }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.email', 1) }}:</strong> <span> {{ $facility->email }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.address', 1) }}:</strong> <span> {{ $facility->address }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.in-charge', 1) }}:</strong> <span> {{ $facility->in_charge }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.reporting-site', 1) }}:</strong> <span> {{ $facility->reporting_site }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.operational-status', 1) }}:</strong> <span> {{ $facility->operational_status== App\Models\Facility::OPERATIONAL? Lang::choice('messages.yes', 1):Lang::choice('messages.no', 1) }}</span>
        </h5>
      </div>
  </div>
</div>
</div>
@stop
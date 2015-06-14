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
  <a class="btn btn-sm btn-info" href="{{ URL::to("assigntestkit/" . $assigntestkit->id . "/edit") }}" >
    <i class="fa fa-edit"></i><span>{{ Lang::choice('messages.edit-test-kit', 1) }}</span>
  </a>
  </span></div>
  <div class="panel-body">
    <div class="panel panel-default">
      <div class="panel-body">
        <h4 class="no-margn view">
          <strong>{{ Lang::choice('messages.site-name', 1) }}:</strong> <span> {{ $assigntestkit->site_name_id }}</span>
        </h4>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.kit-name', 1) }}:</strong> <span> {{ $assigntestkit->kit_name_id }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.lot-no', 1) }}:</strong> <span> {{ $assigntestkit->lot-no}}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.expiry-date', 1) }}:</strong> <span> {{ $assigntestkit->expiry_date }}</span>
        </h5>
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.comments', 1) }}:</strong> <span> {{ $assigntestkit->comments }}</span>
        </h5>
       
        <hr>
        <h5 class="no-margn">
          <strong>{{ Lang::choice('messages.stock-avl', 1) }}:</strong> <span> {{ $assigntestkit->stock_avls== App\Models\AssignTestKit::STOCKAVAILABLE? Lang::choice('messages.Stock Available', 1):Lang::choice('messages.Stock Not Available', 1) }}</span>
        </h5>
      </div>
  </div>
</div>
</div>
@stop
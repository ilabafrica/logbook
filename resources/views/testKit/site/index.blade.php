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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.site-kit', 2) }} <span class="panel-btn">
      <a class="btn btn-sm btn-info" href="{{ URL::to("siteKit/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ Lang::choice('messages.create-site-kit', 1) }}
          </a>
        </span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.site-name', 1) }}</th>
                            <th>{{ Lang::choice('messages.test-kit', 1) }}</th>
                            <th>{{ Lang::choice('messages.lot-no', 1) }}</th>
                            <th>{{ Lang::choice('messages.expiry-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.stock-available', 1) }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sitekits as $siteKit)
                        <tr>
                            <td>{{ $siteKit->site->name }}</td>
                            <td>{{ $siteKit->kit->short_name }}</td>
                            <td>{{ $siteKit->lot_no }}</td>
                            <td>{{ $siteKit->expiry_date }}</td>
                            <td>{{ $siteKit->stock_available==App\Models\SiteKit::AVAILABLE?Lang::choice('messages.stock-availability', 1):Lang::choice('messages.stock-availability', 2) }}</td>
                            <td>
                              <a href="{{ URL::to("siteKit/" . $siteKit->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                              <a href="{{ URL::to("siteKit/" . $siteKit->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                              <a href="{{ URL::to("siteKit/" . $siteKit->id . "/delete") }}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>
                             
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="6">{{ Lang::choice('messages.no-records-found', 1) }}</td>
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
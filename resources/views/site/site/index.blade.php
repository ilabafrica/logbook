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

@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.site-management', 2) }} <span class="panel-btn">
     @if(Auth::user()->can('create-site'))
      <a class="btn btn-sm btn-info" href="{{ URL::to("site/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.create-new-site') }}
          </a>
     @endif
        </span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.site-name', 1) }}</th>
                            <th>{{ Lang::choice('messages.site-type', 1) }}</th>
                            <th>{{ Lang::choice('messages.department', 1) }}</th>
                            <th>{{ Lang::choice('messages.reporting-to-facility', 1) }}</th>
                            <th>{{ Lang::choice('messages.in-charge', 1) }}</th>
                            <th>{{ Lang::choice('messages.email', 1) }}</th>
                            <th>{{ Lang::choice('messages.mobile', 1) }}</th>                           
                            <th></th>
                        </tr>
                    </thead>
                     <tbody>
                        @forelse($sites as $site)
                        <tr>
                            <td>{{ $site->name }}</td>
                            <td>{{ $site->siteType->name }}</td>
                            <td>{{ $site->department }}</td>
                            <td>{{ $site->facility_id}}</td>                            
                            <td>{{ $site->in_charge }}</td>
                            <td>{{ $site->email }}</td>
                            <td>{{ $site->mobile }}</td>
                          
                            <td>
                              <a href="{{ url("site/" . $site->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                              @if(Auth::user()->can('manage-site'))
                              <a href="{{ URL::to("site/" . $site->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                              <a href="{{ URL::to("site/" . $site->id . "/delete") }}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>
                              @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="8">{{ Lang::choice('messages.no-records-found', 1) }}</td>
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
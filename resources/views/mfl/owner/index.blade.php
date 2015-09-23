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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.facility-owner', 2) }} <span class="panel-btn">
       @if(Auth::user()->can('create-facility-owner'))
      <a class="btn btn-sm btn-info" href="{{ URL::to("facilityOwner/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.create-facility-owner') }}
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
                            <th>Name</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilityOwners as $facilityOwner)
                        <tr>
                            <td>{{ $facilityOwner->name }}</td>
                            <td>{{ $facilityOwner->description }}</td>
                            <td>
                              <a href="{{ URL::to("facilityOwner/" . $facilityOwner->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                               @if(Auth::user()->can('manage-facility-owner'))
                              <a href="{{ URL::to("facilityOwner/" . $facilityOwner->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                              <a href="{{ URL::to("facilityOwner/" . $facilityOwner->id . "/delete") }}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>
                               @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="3">No records found.</td>
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
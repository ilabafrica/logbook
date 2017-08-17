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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.facility-type', 2) }} <span class="panel-btn">
      @if(Auth::user()->can('create-facility-type'))
      <a class="btn btn-sm btn-info" href="{{ URL::to("facilityType/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ Lang::choice('messages.create-facility-type', 1) }}
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
                            <th>{{ Lang::choice('messages.name', 1) }}</th>
                            <th>{{ Lang::choice('messages.description', 1) }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilityTypes as $facilityType)
                        <tr>
                            <td>{{ $facilityType->name }}</td>
                            <td>{{ $facilityType->description }}</td>
                            <td>
                              <a href="{{ URL::to("facilityType/" . $facilityType->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                              @if(Auth::user()->can('manage-facility-type'))
                              <a href="{{ URL::to("facilityType/" . $facilityType->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                              <a href="{{ URL::to("facilityType/" . $facilityType->id . "/delete") }}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>
                              @endif
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
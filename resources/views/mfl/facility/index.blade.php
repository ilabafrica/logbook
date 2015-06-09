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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.facility', 2) }} <span class="panel-btn">
      <a class="btn btn-sm btn-info" href="{{ URL::to("facility/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.create-facility') }}
          </a>
        </span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.code', 1) }}</th>
                            <th>{{ Lang::choice('messages.name', 1) }}</th>
                            <th>{{ Lang::choice('messages.county', 1) }}</th>
                            <th>{{ Lang::choice('messages.facility-type', 1) }}</th>
                            <!--<th>{{ Lang::choice('messages.facility-owner', 1) }}</th>
                            <th>{{ Lang::choice('messages.description', 1) }}</th>
                           <th>{{ Lang::choice('messages.nearest-town', 1) }}</th> -->
                            <th>{{ Lang::choice('messages.landline', 1) }}</th>
                          <!--<th>{{ Lang::choice('messages.fax', 1) }}</th>-->
                            <th>{{ Lang::choice('messages.email', 1) }}</th>
                           <!-- <th>{{ Lang::choice('messages.address', 1) }}</th>
                            <th>{{ Lang::choice('messages.town', 1) }}</th>
                            <th>{{ Lang::choice('messages.in-charge', 1) }}</th>
                            <th>{{ Lang::choice('messages.title', 1) }}</th>-->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilities as $facility)
                        <tr>
                            <td>{{ $facility->code }}</td>
                            <td>{{ $facility->name }}</td>
                            <td>{{ $facility->name }}</td>
                            <td>{{ $facility->facilityType->name }}</td>
                            <!-- <td>{{ $facility->facility_owner_id }}</td>
                            <td>{{ $facility->description }}</td>
                            <td>{{ $facility->nearest_town }}</td>-->
                            <td>{{ $facility->landline }}</td>
                       <!-- <td>{{ $facility->fax }}</td>-->
                            <td>{{ $facility->email }}</td>
                        <!--<td>{{ $facility->address }}</td>
                            <td>{{ $facility->town_id }}</td>
                            <td>{{ $facility->in_charge }}</td>
                           <td>{{ $facility->title_id }}</td>-->
                            <td>
                              <a href="{{ URL::to("facility/" . $facility->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                              <a href="{{ URL::to("facility/" . $facility->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                              <a href="{{URL::to("facility/" . $facility->id . "/delete") }}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>
                              
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="3">{{ Lang::choice('messages.no-records-found', 1) }}</td>
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
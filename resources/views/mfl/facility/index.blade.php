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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.facility', 2) }} <span class="panel-btn">
      @if(Auth::user()->can('create-facility'))
      <a class="btn btn-sm btn-info" href="{{ URL::to("facility/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.create-facility') }}
          </a>
          @endif
          @if(Auth::user()->can('import-facility-data'))
          <a class="btn btn-sm btn-info" href="{{ URL::to("import/facility") }}" >
            <span class="glyphicon glyphicon-download"></span>
                {{ trans('messages.import-facility-data') }}
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
                            <th>{{ Lang::choice('messages.code', 1) }}</th>
                            <th>{{ Lang::choice('messages.name', 1) }}</th>
                            <th>{{ Lang::choice('messages.sub-county', 1) }}</th>
                            <th>{{ Lang::choice('messages.facility-type', 1) }}</th>
                            <th>{{ Lang::choice('messages.landline', 1) }}</th>
                           <!-- <th>{{ Lang::choice('messages.email', 1) }}</th>
                            <th>{{ Lang::choice('messages.reporting-site', 1) }}</th>-->
                           
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilities as $facility)
                        <tr>
                            <td>{!! $facility->code !!}</td>
                            <td>{!! $facility->name !!}</td>
                            <td>{!! $facility->subCounty->name !!}</td>
                            <td>{!! $facility->facilityType->name !!}</td>
                            <td>{!! $facility->landline !!}</td>
                            <!--<td>{!! $facility->email !!}</td>
                            <td>{!! $facility->reporting_site !!}</td>-->

                          
                            <td>
                              <a href="{!! url("facility/" . $facility->id) !!}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                              @if(Auth::user()->can('manage-facility'))
                              <a href="{!! url("facility/" . $facility->id . "/edit") !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                              <a href="{!! url("facility/" . $facility->id . "/delete") !!}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>
                              @endif
                              @if(Auth::user()->can('view-reports'))
                              <a href="{!! url("htc/" . $facility->id) !!}" class="btn btn-danger btn-sm"><i class="fa fa-edit"></i><span> Run Reports</span></a>
                             @endif
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
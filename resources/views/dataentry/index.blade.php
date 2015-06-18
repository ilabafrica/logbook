@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <a href="#"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.data-entry', 1) }}</a>
            </li>
        </ol>
    </div>
</div>

@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.facility', 2) }} 


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
                           
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilities as $facility)
                        <tr>
                            <td>{{ $facility->code }}</td>
                            <td>{{ $facility->name }}</td>
                            <td>{{ $facility->county->name}}</td>                          
                            <td>{{ $facility->facilityType->name }}</td>
                          
                            <td>
                            <a href="{{ URL::to('serial/'.$facility->id.'/index')}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i><span> Serial</span></a>
                            <a href="{{ URL::to('summaryserial')}}"class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span>  Serial Summary</span></a>
                             <a href="{{ URL::to('parallel')}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i><span> Parallel</span></a>
                            <a href="{{ URL::to('summaryparallel')}}"class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span>  Parallel Summary</span></a>
                             
                              
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
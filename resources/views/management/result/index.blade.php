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

{!! Form::open(array('route' => 'result.index', 'id' => 'form-result', 'class' => 'form-horizontal')) !!}
  
            <div class="form-group">
                    {!! Form::label('facility_id', Lang::choice('messages.facility-name', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-2">

                        {!! Form::select('facility', array(''=>trans('messages.select-facility'))+$facilities,'', 
                            array('class' => 'form-control', 'id' => 'facility')) !!}
                    </div>
                    {!! Form::button("<i class='glyphicon glyphicon-ok-circle'></i> ".Lang::choice('messages.submit', 1), 
                          array('class' => 'btn btn-success', 'onclick' => 'submit()')) !!}
                     </div>
         
 {!! Form::close() !!} 
<br />


@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.result-management', 2) }} 
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
<<<<<<< HEAD
                           
                            <th rowspan="2">{{ Lang::choice('messages.site', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.algo', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.start-date', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.end-date', 1) }}</th>
                            <th rowspan="1" colspan="3">{{ Lang::choice('messages.test1', 1) }}</th>
                            <th rowspan="1" colspan="3" >{{ Lang::choice('messages.test2', 1) }}</th>
                            <th rowspan="1" colspan="3">{{ Lang::choice('messages.test3', 1) }}</th>                                                                              
                            <th rowspan="2"></th>
                        </tr>
                        <tr>
                            <th>R </th>
                            <th> NR</th>
                            <th>Inv </th>
                             <th>R </th>
                            <th> NR</th>
                            <th>Inv </th>
                             <th>R </th>
                            <th> NR</th>
                            <th>Inv </th>
                           
                        </tr>
                    </thead>
                    <tbody>
                            @forelse($serials as $serial)
                            <tr>
                            <td>{{ $serial->site->site_name}}</td>
                            <td>Serial</td>
                            <td>{{ $serial->start_date }}</td>
                            <td>{{ $serial->end_date }}</td>
                            <td>{{ $serial->test_kit1R }}</td>
                            <td>{{ $serial->test_kit1NR }}</td>
                            <td>{{ $serial->test_kit1Inv }}</td>
                            <td>{{ $serial->test_kit2R }}</td>
                            <td>{{ $serial->test_kit2NR }}</td>
                            <td>{{ $serial->test_kit2Inv }}</td>
                            <td>{{ $serial->test_kit3R }}</td>
                            <td>{{ $serial->test_kit3NR }}</td>
                            <td>{{ $serial->test_kit3Inv }}</td>
                            
                           <td>
                            <a href="{{ URL::to("serial/" . $serial->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                             
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="3">{{ Lang::choice('messages.no-records-found', 1) }}</td>
                        </tr>
                        @endforelse
                         @forelse($parallels as $parallel)
                            <tr>
                            <td>{{ $parallel->test_site_id }}</td>
                            <td>Parallel</td>
                            <td>{{ $parallel->start_date }}</td>
                            <td>{{ $parallel->end_date }}</td>
                            <td>{{ $parallel->test_kit1R }}</td>
                            <td>{{ $parallel->test_kit1NR }}</td>
                            <td>{{ $parallel->test_kit1Inv }}</td>
                            <td>{{ $parallel->test_kit2R }}</td>
                            <td>{{ $parallel->test_kit2NR }}</td>
                            <td>{{ $parallel->test_kit2Inv }}</td>
                            <td>{{ $parallel->test_kit3R }}</td>
                            <td>{{ $parallel->test_kit3NR }}</td>
                            <td>{{ $parallel->test_kit3Inv }}</td>
                            
                           <td>
                             <a href="{{ URL::to("parallel/" . $parallel->id . "/edit") }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                             
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="3">{{ Lang::choice('messages.no-records-found', 1) }}</td>
                        </tr>
                        @endforelse
=======
                            <th>{{ Lang::choice('messages.facility-name', 1) }}</th>
                            <th>{{ Lang::choice('messages.site', 1) }}</th>
                            <th>{{ Lang::choice('messages.algo', 1) }}</th>
                            <th>{{ Lang::choice('messages.start-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.end-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.test1', 1) }}</th>
                             <th>{{ Lang::choice('messages.test2', 1) }}</th>
                              <th>{{ Lang::choice('messages.test3', 1) }}</th>
                                                       
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                      
                        <tr>
                          <td colspan="3">{{ Lang::choice('messages.no-records-found', 1) }}</td>
                        </tr>
                       
>>>>>>> master
                    </tbody>
                </table>
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
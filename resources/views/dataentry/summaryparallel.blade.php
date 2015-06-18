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
{!! Form::open(array('route' => 'summaryparallel.index', 'id' => 'form-result', 'class' => 'form-horizontal')) !!}
  
             <div class="form-group">
                    {!! Form::label('site_id', Lang::choice('messages.site-name', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-2">
                        {!! Form::select('sites', array(''=>trans('messages.select-site'))+$sites,'', 
                            array('class' => 'form-control', 'id' => 'site')) !!}
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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.page-summary-data-entry-parallel-algorithm', '1') }}</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover ">
                    <thead>
                        <tr>
                            
                            <th>{{ Lang::choice('messages.site', 1) }}</th>                        
                            <th>{{ Lang::choice('messages.start-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.end-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.total-tests', 1) }}</th>
                            <th >{{ Lang::choice('messages.test1R', 1) }}</th>
                            <th >{{ Lang::choice('messages.test1NR', 1) }}</th>
                            <th>{{ Lang::choice('messages.test1Inv', 1) }}</th>
                            <th >{{ Lang::choice('messages.test2R', 1) }}</th>
                            <th >{{ Lang::choice('messages.test2NR', 1) }}</th>
                            <th>{{ Lang::choice('messages.test2Inv', 1) }}</th>
                            <th >{{ Lang::choice('messages.test3R', 1) }}</th>
                            <th >{{ Lang::choice('messages.test3NR', 1) }}</th>
                            <th>{{ Lang::choice('messages.test3Inv', 1) }}</th>
                            <th>{{ Lang::choice('messages.%pos', 1) }}</th>
                            <th>{{ Lang::choice('messages.positive-agr', 1) }}</th>
                            <th>{{ Lang::choice('messages.overall-agr', 1) }}</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                            @forelse($parallels as $parallel)
                            <tr>
                            <td>{{ $parallel->test_site_id }}</td>
                            <td>{{ $parallel->start_date }}</td>
                            <td>{{ $parallel->end_date }}</td>
                            <td>
                                {{($parallel->test_kit1R + $parallel->test_kit1NR + $parallel->test_kit1Inv +
                                          $parallel->test_kit2R +$parallel->test_kit2NR + $parallel->test_kit2Inv +
                                          $parallel->test_kit3R + $parallel->test_kit3NR +$parallel->test_kit3Inv)}}</td>
                            </td>
                            <td>{{ $parallel->test_kit1R }}</td>
                            <td>{{ $parallel->test_kit1NR }}</td>
                            <td>{{ $parallel->test_kit1Inv }}</td>
                            <td>{{ $parallel->test_kit2R }}</td>
                            <td>{{ $parallel->test_kit2NR }}</td>
                            <td>{{ $parallel->test_kit2Inv }}</td>
                            <td>{{ $parallel->test_kit3R }}</td>
                            <td>{{ $parallel->test_kit3NR }}</td>
                            <td>{{ $parallel->test_kit3Inv }}</td>
                            <td>{{round($parallel->positive / ( $parallel->test_kit1R + $parallel->test_kit1NR + $parallel->test_kit1Inv +
                                                              $parallel->test_kit2R + $parallel->test_kit2NR + $parallel->test_kit2Inv +
                                                              $parallel->test_kit3R + $parallel->test_kit3NR + $parallel->test_kit3Inv)*100,2)}}</td>
                            <td>@if($parallel->test_kit1R >0){{round(($parallel->test_kit2R/$parallel->test_kit1R)*100,2) }}
                            @else
                            @endif</td>
                            <td>{{round((($parallel->test_kit2R + $parallel->test_kit1NR)/($parallel->test_kit1R  + $parallel->test_kit1NR + $parallel->test_kit1Inv)- $parallel->test_kit1Inv)*100,2) }}
                            </td>
                           
                           <td>
                             <a href="{{ URL::to("summaryparallel/" . $parallel->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                             
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
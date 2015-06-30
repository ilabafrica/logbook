@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> {{ Lang::choice('messages.logbook-data', 1) }}
            </li>
        </ol>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.logbook-data', '1') }}<span class="panel-btn">
        <a class="btn btn-sm btn-info" href="{{ URL::to("logbookdata/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.generate-report') }}
          </a>
          </span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th rowspan="2">{{ Lang::choice('messages.site', 1) }}</th> 
                            <th rowspan="2">{{ Lang::choice('messages.algorithm', 1) }}</th>  
                             <th rowspan="2">{{ Lang::choice('messages.start-date', 1) }}</th> 
                              <th rowspan="2">{{ Lang::choice('messages.end-date', 1) }}</th>                       
                            <th rowspan="2">{{ Lang::choice('messages.total-tests', 1) }}</th>
                            <th rowspan="1" colspan="3" class="success">{{ Lang::choice('messages.test1', 1) }}</th>
                            <th rowspan="1" colspan="3" class="danger">{{ Lang::choice('messages.test2', 1) }}</th>
                            <th rowspan="1" colspan="3" class="info">{{ Lang::choice('messages.test3', 1) }}</th>   
                            <th rowspan="2">{{ Lang::choice('messages.final-pos', 1) }}</th>                                                                           
                            <th rowspan="2">{{ Lang::choice('messages.%pos', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.positive-agr', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.overall-agr', 1) }}</th>
                            <th rowspan="2"></th>                            
                        </tr>
                         <tr>
                            @foreach($testKits as $testKit)
                                <?php
                                    if($testKit['id'] == App\Models\Htc::TESTKIT1)
                                        $class = 'success';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT2)
                                        $class = 'danger';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT3)
                                        $class = 'info';
                                ?>
                                <td class="{!! $class !!}">{!! Lang::choice('messages.reactive', 1) !!}</td>
                                <td class="{!! $class !!}">{!! Lang::choice('messages.non-reactive', 1) !!}</td>
                                <td class="{!! $class !!}">{!! Lang::choice('messages.invalid', 1) !!}</td>
                            @endforeach                         
                        </tr>
                    </thead>
                    <tbody>
                   
                    </tbody>
                </table>
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
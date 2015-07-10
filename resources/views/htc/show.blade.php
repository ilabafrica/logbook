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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.page-summary', '1') }}</div>
     <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover ">
                    <thead>
                    <tr> <th colspan="1">{{ Lang::choice('messages.test', 1) }}</th> 
                            <th colspan="3">{{ Lang::choice('messages.test1', 1) }}</th>  
                             <th colspan="3">{{ Lang::choice('messages.test2', 1) }}</th> 
                              <th colspan="3">{{ Lang::choice('messages.test3', 1) }}</th>   
                              </tr>
                       
                    <tr>
                          <td>{!! Lang::choice('messages.kit-name', 1) !!}</td>
                            @foreach($testKits as $testKit)
                                <?php
                                    if($testKit['id'] == App\Models\Htc::TESTKIT1)
                                        $class = 'success';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT2)
                                        $class = 'danger';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT3)
                                        $class = 'info';
                                ?>

                                <td class="{!! $class !!}"></td>
                                <td class="{!! $class !!}"></td>
                                <td class="{!! $class !!}"></td>
                            @endforeach                         
                    </tr>
                    <tr>
                          <td>{!! Lang::choice('messages.lot-no', 1) !!}</td>
                            @foreach($testKits as $testKit)
                                <?php
                                    if($testKit['id'] == App\Models\Htc::TESTKIT1)
                                        $class = 'success';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT2)
                                        $class = 'danger';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT3)
                                        $class = 'info';
                                ?>

                                <td class="{!! $class !!}"></td>
                                <td class="{!! $class !!}"></td>
                                <td class="{!! $class !!}"></td>
                            @endforeach                         
                    </tr>
                    <tr>
                          <td>{!! Lang::choice('messages.expiry-date', 1) !!}</td>
                            @foreach($testKits as $testKit)
                                <?php
                                    if($testKit['id'] == App\Models\Htc::TESTKIT1)
                                        $class = 'success';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT2)
                                        $class = 'danger';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT3)
                                        $class = 'info';
                                ?>

                                <td class="{!! $class !!}"></td>
                                <td class="{!! $class !!}"></td>
                                <td class="{!! $class !!}"></td>
                            @endforeach                         
                    </tr>
                       
                    <tr>
                          <td>{!! Lang::choice('messages.test-results', 1) !!}</td>
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
                    <tr>
                          <td>{!! Lang::choice('messages.no-of-tests', 1) !!}</td>
                            @foreach($testKits as $testKit)
                                <?php
                                    if($testKit['id'] == App\Models\Htc::TESTKIT1)
                                        $class = 'success';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT2)
                                        $class = 'danger';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT3)
                                        $class = 'info';
                                ?>

                                <td class="{!! $class !!}"></td>
                                <td class="{!! $class !!}"></td>
                                <td class="{!! $class !!}"></td>
                            @endforeach                         
                     </tr>
                    </thead>
                    <tbody>
                   
                    </tbody>
                </table>
<div class="col-sm-8" align="right">
            <table class="table table-striped table-bordered table-hover ">
                    <thead>
                    <tr> <th colspan="10">{{ Lang::choice('messages.final-result', 1) }}</th> </tr>
                       
                    <tr>
                          
                                <td class="success">{{ Lang::choice('messages.positive', 1) }}</td>
                                <td class="danger">{{ Lang::choice('messages.negative', 1) }}</td>
                                <td class="info">{{ Lang::choice('messages.indeterminate', 1) }}</td>
                                <td class="success">{{ Lang::choice('messages.overall-agreement', 1) }}</td>
                                <td class="danger">{{ Lang::choice('messages.positive-agreement', 1) }}</td>
                                                 
                    </tr>    
                    <tr>
                                <td class="success">{{ $htcData->positive }}</td>
                                <td class="danger">{{ $htcData->negative }}</td>
                                <td class="info">{{ $htcData->indeterminate}}</td>
                                <td class="success"></td>
                                <td class="danger"></td>
                                                 
                    </tr>                                                       
                    </thead>
                    <tbody>
                   
                    </tbody>
                </table>
            </div>
             </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
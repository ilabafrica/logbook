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
                        <tr>                          
                            <th>{{ Lang::choice('messages.facility', 1) }}:</th>                        
                            <th>{{ Lang::choice('messages.site-name', 1) }}:{{ $serial->test_site_id}}</th>
                            <th>{{ Lang::choice('messages.algorithm', 1)}}:Serial</th>
                                                     
                        </tr>
                    </thead>                                     
                </table>
                <table  class="table table-striped table-bordered table-hover">

                <thead>
                        <tr>                          
                            <th> {{ Lang::choice('messages.test', 1) }}</th>                         
                            <th>{{ Lang::choice('messages.test1', 1) }}</th>                        
                            <th>{{ Lang::choice('messages.test2', 1)}}</th>
                            <th>{{ Lang::choice('messages.test3', 1)}}</th>
                                                     
                        </tr>
                    </thead>   
                 <tbody>
                     <tr>                          
                            <td>{{ Lang::choice('messages.kit-name', 1) }}</td>                        
                            <td>{{ $serial->test_kit1_id}}</td>
                            <td>{{ $serial->test_kit2_id}}</td>
                            <td>{{ $serial->test_kit3_id}}</td>
                                                     
                        </tr>
                         <tr>                          
                            <td>{{ Lang::choice('messages.lot-no', 1) }}</td>                        
                            <td>{{ $serial->test_kit1_id}}</td>
                            <td>{{ $serial->test_kit2_id}}</td>
                            <td>{{ $serial->test_kit3_id}}</td>
                                                     
                        </tr>
                        <tr>                          
                            <td>{{ Lang::choice('messages.expiry-date', 1) }}</td>                        
                            <td>{{ $serial->test_kit1_id}}</td>
                            <td>{{ $serial->test_kit2_id}}</td>
                            <td>{{ $serial->test_kit3_id}}</td>
                                                     
                        </tr>
                         <tr>                          
                            <td>{{ Lang::choice('messages.test-results', 1) }}</td> 
                            <td><table  class="table  table-bordered ">                      
                            <td style= "width:100px">R</td>
                            <td style= "width:100px">NR</td>
                            <td style= "width:100px">Inv</td></table></td>
                            <td><table  class="table  table-bordered ">                      
                            <td style= "width:100px">R</td>
                            <td style= "width:100px">NR</td>
                            <td style= "width:100px">Inv</td></table></td>
                            <td><table  class="table  table-bordered ">                      
                            <td style= "width:100px">R</td>
                            <td style= "width:100px">NR</td>
                            <td style= "width:100px">Inv</td></table></td>
                                                    
                        </tr>
                        <tr>                          
                            <td>{{ Lang::choice('messages.no-of-tests', 1) }}
                            <td><table  class="table  table-bordered ">                      
                            <td style= "width:100px">{{ $serial->test_kit1R }}</td>
                            <td style= "width:100px">{{ $serial->test_kit1NR }}</td>
                            <td style= "width:100px">{{ $serial->test_kit1Inv }}</td>
                            </table></td>
                            <td><table  class="table  table-bordered ">                      
                            <td style= "width:100px">{{ $serial->test_kit2R }}</td>
                            <td style= "width:100px">{{ $serial->test_kit2NR }}</td>
                            <td style= "width:100px">{{ $serial->test_kit2Inv }}</td></table></td>
                            <td><table  class="table  table-bordered ">                      
                            <td style= "width:100px">{{ $serial->test_kit3R }}</td>
                            <td style= "width:100px">{{ $serial->test_kit3NR }}</td>
                            <td style= "width:100px">{{ $serial->test_kit3Inv }}</td></table></td></td>  
                                                    
                        </tr>
                    </tbody></table>
                     <table class="table table-striped table-bordered table-hover ">
                    <thead>
                    <tr><th colspan="5">Final Result</th></tr>
                        <tr>                          
                            <th>{{ Lang::choice('messages.positive', 1) }}</th>                        
                            <th>{{ Lang::choice('messages.negative', 1) }}</th>
                            <th>{{ Lang::choice('messages.indeterminate', 1)}}</th>
                            <th>{{ Lang::choice('messages.overall-agreement', 1)}}</th>
                            <th>{{ Lang::choice('messages.positive-agreement', 1)}}</th>
                                                     
                        </tr>
                    </thead>   
                    <tbody>
                        <tr>                          
                            <td>{{ $serial->positive }}</td>                        
                            <td>{{ $serial->negative }}</td>
                            <td>{{ $serial->indeterminate}}</td>
                            <td>{{round((($serial->test_kit2R + $serial->test_kit1NR)/($serial->test_kit1R  + $serial->test_kit1NR + $serial->test_kit1Inv)- $serial->test_kit1Inv)*100,2) }}
                            </td>
                          <td>@if($serial->test_kit1R >0){{round(($serial->test_kit2R/$serial->test_kit1R)*100,2) }}
                            @else
                            @endif</td>
                                                     
                        </tr>
                    </tbody>                                  
                </table>
            </div>
           
           
               
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
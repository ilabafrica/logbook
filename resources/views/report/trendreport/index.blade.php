@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> {{ Lang::choice('messages.trend-report', 1) }}
            </li>
        </ol>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.trend-report', '1') }}
    <span class="panel-btn">
        <a class="btn btn-sm btn-info" href="{{ URL::to("trendreport/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.generate-report') }}
          </a>
    </span></div>
   <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
           <ul class="nav nav-tabs default">
           <li class="active"><a href="#trend" data-toggle="tab" aria-expanded="true">{!! Lang::choice('messages.trend-report', 1) !!}</a>
                </li>
                <li class=""><a href="#bar" data-toggle="tab" aria-expanded="true">{!! Lang::choice('messages.bar-chart', 1) !!}</a>
                </li>
                <li class=""><a href="#spider" data-toggle="tab" aria-expanded="false">{!! Lang::choice('messages.spider-chart', 1) !!}</a>
                </li>
            </ul>
            <div class="tab-content">
            <div class="tab-pane fade active in" id="trend" style="padding-top:20px;">
                
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
            
                <div class="tab-pane fade active in" id="spider" style="padding-top:20px;">
                </div>
                <div class="tab-pane fade active in" id="bar" style="padding-top:20px;"></div>
            </div>               
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
<script src="{{ URL::asset('fusioncharts/fusioncharts.js') }}"></script>
<script src="{{ URL::asset('fusioncharts/themes/fusioncharts.theme.ocean.js') }}"></script>

<script type="text/javascript">
/* Return bar chart */
FusionCharts.ready(function(){
    var revenueChart = new FusionCharts();
  revenueChart.render("bar");
});
/* Return spider chart */
FusionCharts.ready(function(){
    var revenueChart = new FusionCharts();
  revenueChart.render("spider");
});
</script>







@stop
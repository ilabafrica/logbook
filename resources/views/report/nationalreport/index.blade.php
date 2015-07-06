@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> {{ Lang::choice('messages.national-report', 1) }}
            </li>
        </ol>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.national-report', '1') }}
    </div>
   <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
          
              map of kenya comes here           
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
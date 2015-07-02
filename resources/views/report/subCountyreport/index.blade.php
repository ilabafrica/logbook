@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> {{ Lang::choice('messages.sub-county-report', 1) }}
            </li>
        </ol>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.sub-county-report', '1') }}
    </div>
   <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
           <ul class="nav nav-tabs default">
                <li class="active"><a href="#counties" data-toggle="tab" aria-expanded="true">{!! Lang::choice('messages.facility-list', 1) !!}</a></li>            </li>
                <li class=""><a href="#pos" data-toggle="tab" aria-expanded="true">{!! Lang::choice('messages.%pos-comparison', 1) !!}</a></li>                
                <li class=""><a href="#pos-agr" data-toggle="tab" aria-expanded="false">{!! Lang::choice('messages.%pos-agr-comparison', 1) !!}</a></li>
                <li class=""><a href="#over-agr" data-toggle="tab" aria-expanded="false">{!! Lang::choice('messages.%over-agr-comparison', 1) !!}</a></li>
                <li class=""><a href="#inv" data-toggle="tab" aria-expanded="false">{!! Lang::choice('messages.%inv-comparison', 1) !!}</a></li>
             
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="counties" style="padding-top:20px;">
                  <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.code', 1) }}</th>
                            <th>{{ Lang::choice('messages.name', 1) }}</th>
                            <th>{{ Lang::choice('messages.sub-county', 1) }}</th>                                                     
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilities as $facility)
                        <tr>
                            <td>{!! $facility->code !!}</td>
                            <td>{!! $facility->name !!}</td>
                            <td>{!! $facility->subCounty->name !!}</td>
                                                      
                            <td>
                              <a href="{!! url("facilityreport/" . $facility->id) !!}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
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
                <div class="tab-pane fade active in" id="pos" style="padding-top:20px;"></div>
                <div class="tab-pane fade active in" id="pos-agr" style="padding-top:20px;"></div>
                 <div class="tab-pane fade active in" id="over-agr" style="padding-top:20px;"></div>
                  <div class="tab-pane fade active in" id="inv" style="padding-top:20px;"></div>
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
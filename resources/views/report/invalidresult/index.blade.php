@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> {{ Lang::choice('messages.%invalidResult-report', 1) }}
            </li>
        </ol>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.%invalidResult-report', '1') }}
    </div>
   <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                 <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('start-date', Lang::choice('messages.start-date', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <div class=" input-group input-append date datepicker" id="start-date" >
                                        {!! Form::text('start_date', Input::old('start_date'), array('class' => 'form-control')) !!}
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('end-date', Lang::choice('messages.end-date', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <div class=" input-group input-append date datepicker" id="end-date" >
                                        {!! Form::text('end_date', Input::old('end_date'), array('class' => 'form-control')) !!}
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                 {!! Form::label('county_id', Lang::choice('messages.county', 1), array('class' => 'col-sm-3 control-label')) !!}
                                 <div class="col-sm-8">
                                 {!! Form::select('county', array(''=>trans('messages.select-county')),'', 
                                 array('class' => 'form-control', 'id' => 'county')) !!}
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-4">
                            <div class="form-group">
                                 {!! Form::label('sub-county_id', Lang::choice('messages.sub-county', 1), array('class' => 'col-sm-3 control-label')) !!}
                                 <div class="col-sm-8">
                                 {!! Form::select('subCounty', array(''=>trans('messages.select-sub-county')),'', 
                                 array('class' => 'form-control', 'id' => 'subCounty')) !!}
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-4">
                            <div class="form-group">
                                 {!! Form::label('facility_id', Lang::choice('messages.facility', 1), array('class' => 'col-sm-3 control-label')) !!}
                                 <div class="col-sm-8">
                                 {!! Form::select('facility', array(''=>trans('messages.select-facility')),'', 
                                 array('class' => 'form-control', 'id' => 'county')) !!}
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    </br>
           <ul class="nav nav-tabs default">
                <li class="active"><a href="#facilities" data-toggle="tab" aria-expanded="true">{!! Lang::choice('messages.facility-level', 1) !!}</a></li>            
                <li class=""><a href="#pos" data-toggle="tab" aria-expanded="false">{!! Lang::choice('messages.subCounty-level', 1) !!}</a></li>                
                <li class=""><a href="#pos-agr" data-toggle="tab" aria-expanded="false">{!! Lang::choice('messages.county-level', 1) !!}</a></li>
                <li class=""><a href="#over-agr" data-toggle="tab" aria-expanded="false">{!! Lang::choice('messages.national-level', 1) !!}</a></li>
           </ul>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="facilities" style="padding-top:20px;"></div>      
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
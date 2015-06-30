@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <a href="#"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.testkit-use', 1) }}</a>
            </li>
        </ol>
    </div>
</div>

@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
   <div class="panel panel-primary">
   <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.testkit-use', '1') }}</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <!-- Begin form --> 
                @if($errors->all())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
                </div>
                @endif
                {!! Form::open(array('route' => 'htc.saveLogbook', 'id' => 'form-add-htc', 'class' => 'form-horizontal')) !!}
                    <!-- CSRF Token -->
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <!-- ./ csrf token -->
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
                               {!! Form::label('facility_id', Lang::choice('messages.facility', 1), array('class' => 'col-sm-4 control-label')) !!}
                                  <div class="col-sm-8">
                                     {!! Form::select('facility', array(''=>trans('messages.select-facility'))+$facilities,'', 
                            array('class' => 'form-control', 'id' => 'facility')) !!}
                                </div>
                            </div>
                        </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                               {!! Form::label('testing_algorithm', Lang::choice('messages.testing-algorithm', 1), array('class' => 'col-sm-4 control-label')) !!}
                              <div class="col-sm-8">
                                    {!! Form::select('testing_algorithm', array('serial' => 'Serial', 'parallel' => 'Parallel'),'', 
                            array('class' => 'form-control', 'id' => 'testing_algorithm'))  !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      
                        <div class="col-sm-4">
                            <div class="form-group">
                               {!! Form::label('test_site', Lang::choice('messages.test-site', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                     {!! Form::select('site', array(''=>trans('messages.select-site'))+$sites,'', 
                                        array('class' => 'form-control', 'id' => 'site')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('report_frequency', Lang::choice('messages.report-frequency', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                {!! Form::select('report_frequency', array('monthly' => 'Monthly', 'yearly' => 'Yearly', 'quarterly' => 'Quarterly', 'adhoc' => 'Adhoc'),'', 
                                array('class' => 'form-control', 'id' => 'report_frequency'))  !!}  
                                 </div>
                               
                            </div>
                        </div>
                    </div>
                   
                    
                       <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                        {!! Form::button("<i class='glyphicon glyphicon-ok-circle'></i> ".Lang::choice('messages.save', 1), 
                              array('class' => 'btn btn-success', 'onclick' => 'submit()')) !!}
                              {!! Form::button("<i class='glyphicon glyphicon-remove-circle'></i> ".'Reset', 
                              array('class' => 'btn btn-default', 'onclick' => 'reset()')) !!}
                        <a href="#" class="btn btn-s-md btn-warning"><i class="glyphicon glyphicon-ban-circle"></i> {{ Lang::choice('messages.cancel', 1) }}</a>
                        </div>
                    </div>
                {!! Form::close() !!} 
                <!-- End form -->
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
    </div>
</div>
@stop
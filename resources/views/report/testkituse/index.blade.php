@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}
            </li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.testkit-use', '1') }}</div>
    <div class="panel-body">
        <div class="col-lg-6 main">
            <!-- Begin form --> 
            @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
            @endif
            {!! Form::open(array('route' => 'testkituse.store', 'id' => 'form-testkit-use', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                <div class="form-group">
                    {!! Form::label('start_date', Lang::choice('messages.start-date', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-offset-4 col-sm-7 input-group input-append date datepicker" id="date-of-birth" style="margin-left:170px;">
                        {!! Form::text('start_date', Input::old('start_date'), array('class' => 'form-control')) !!}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('end_date', Lang::choice('messages.end-date', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-offset-4 col-sm-7 input-group input-append date datepicker" id="date-of-birth" style="margin-left:170px;">
                        {!! Form::text('end_date', Input::old('end_date'), array('class' => 'form-control')) !!}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('facility_id', Lang::choice('messages.facility', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('facility', array(''=>trans('messages.select-facility')),'', 
                            array('class' => 'form-control', 'id' => 'facility')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('test_site', Lang::choice('messages.test-site', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('test_site', Input::old('test_site'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                   <div class="form-group">
                    {!! Form::label('testing_algorithm', Lang::choice('messages.testing-algorithm', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">  
                        {!! Form::text('testing_algorithm', Input::old('testing_algorithm'), array('class' => 'form-control')) !!}
                         </div>
                </div>
                             
                <div class="form-group">
                    {!! Form::label('report_frequency', Lang::choice('messages.report-frequency', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('report_frequency', array(''=>trans('messages.select-frequency')),'', 
                            array('class' => 'form-control', 'id' => 'report_frequency')) !!}   
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
    </div>
</div>
@stop
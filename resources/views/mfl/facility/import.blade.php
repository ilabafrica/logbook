@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
             <li class="active">
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.import-dataset-form-other-facility', '1') }}</div>
    <div class="panel-body">
        <div class="col-lg-6 main">
            <!-- Begin form --> 
            @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
            @endif
            {!! Form::open(array('route' => 'importfacilitydata.store', 'id' => 'form-add-facility-data', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                <div class="form-group">
                    {!! Form::label('data_manager', Lang::choice('messages.data-manager', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('data_manager', Input::old('data-manager'), array('class' => 'form-control')) !!}
                    </div>
                </div>
               <!-- <div class="form-group">
                    {!! Form::label('facility_id', Lang::choice('messages.facility-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('facility', array(''=>trans('messages.select-facility')),'', 
                            array('class' => 'form-control', 'id' => 'facility')) !!}
                    </div>
                </div>-->
                <div class="form-group">
                    {!! Form::label('name', Lang::choice('messages.facility-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">  
                        {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                         </div>
                </div>
                   <div class="form-group">
                    {!! Form::label('reporting_period', Lang::choice('messages.reporting-period', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">  
                        {!! Form::text('reporting-period', Input::old('reporting-period'), array('class' => 'form-control')) !!}
                         </div>
                </div>
                <div class="form-group">
                    {!! Form::label('name', Lang::choice('messages.upload-file', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::file(Lang::choice('messages.excel', 1), null, ['class' => 'form-control', 'id' => Lang::choice('messages.excel', 1)]) !!}
                    </div>
                </div>
             
                <div class="form-group">
                    {!! Form::label('reference_info', Lang::choice('messages.reference-info', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('reference_info', Input::old('reference_info'), array('class' => 'form-control')) !!}
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
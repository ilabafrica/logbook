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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.create-new-site', '1') }}</div>
    <div class="panel-body">
        <div class="col-lg-6 main">
            <!-- Begin form --> 
            @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
            @endif
            {!! Form::open(array('route' => 'site.store', 'id' => 'form-add-new-site', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
               <div class="form-group">
                    {!! Form::label('facility_id', Lang::choice('messages.reporting-to-facility', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('facility', array(''=>trans('messages.select-facility')),'', 
                            array('class' => 'form-control', 'id' => 'facility')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('site_id', Lang::choice('messages.site-id', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('site_id', Input::old('site_id'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('site_name', Lang::choice('messages.site-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('site_name', Input::old('site_name'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                 <div class="form-group">
                    {!! Form::label('site_type_id', Lang::choice('messages.site-type', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('site_type', array(''=>trans('messages.select-site-type')),'', 
                            array('class' => 'form-control', 'id' => 'site_type')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('address', Lang::choice('messages.address', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::textarea('address', Input::old('address'), 
                            array('class' => 'form-control', 'rows' => '3')) !!}
                    </div>
                </div>
                 <div class="form-group">
                    {!! Form::label('nearest_town', Lang::choice('messages.nearest-town', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('nearest_town', Input::old('nearest_town'), array('class' => 'form-control')) !!}
                    </div>
                </div>
            <div class="form-group">
                    {!! Form::label('county', Lang::choice('messages.county', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('county', array(''=>trans('messages.select-county')),'', 
                            array('class' => 'form-control', 'id' => 'county')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('department', Lang::choice('messages.department', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('department', Input::old('department'), array('class' => 'form-control')) !!}
                    </div>
                </div>           
                <div class="form-group">
                    {!! Form::label('landline', Lang::choice('messages.landline', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('landline', Input::old('landline'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('mobile', Lang::choice('messages.mobile', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('mobile', Input::old('mobile'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('email', Lang::choice('messages.email', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('email', Input::old('email'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                
                <div class="form-group">
                    {!! Form::label('in_charge', Lang::choice('messages.in-charge', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('in_charge', Input::old('in_charge'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('latitude', Lang::choice('messages.latitude', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                         {!! Form::text('latitude', Input::old('latitude'), array('class' => 'form-control')) !!}
                   </div>
                </div>
                <div class="form-group">
                    {!! Form::label('longitude', Lang::choice('messages.longitude', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::text('longitude', Input::old('longitude'), array('class' => 'form-control')) !!}
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
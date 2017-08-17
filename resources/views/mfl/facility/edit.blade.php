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

@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.edit-facility', '1') }}</div>
    <div class="panel-body">
        <div class="col-lg-6 main">
            <!-- Begin form --> 
            @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
            @endif
            {!! Form::model($facility, array('route' => array('facility.update', $facility->id), 
        'method' => 'PUT', 'id' => 'form-edit-facility', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                <div class="form-group">
                    {!! Form::label('code', Lang::choice('messages.code', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('code', Input::old('code'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('name', Lang::choice('messages.name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('facility_type_id', Lang::choice('messages.facility-type', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('facility_type', array(''=>trans('messages.select-facility-type'))+$facilityTypes,
                            old('facilityType') ? old('facilityType') : $facilityType, 
                            array('class' => 'form-control', 'id' => 'facility_type')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('facility_owner_id', Lang::choice('messages.facility-owner', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('facility_owner', array(''=>trans('messages.select-facility-owner'))+$facilityOwners,
                            old('facilityOwner') ? old('facilityOwner') : $facilityOwner, 
                            array('class' => 'form-control', 'id' => 'facility_owner')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('sub_county_id', Lang::choice('messages.sub-county', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('sub_county', array(''=>trans('messages.select-sub-county'))+$subCounties,
                        old('subCounty') ? old('subCounty') : $subCounty,
                            array('class' => 'form-control', 'id' => 'sub_county')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('nearest_town', Lang::choice('messages.nearest-town', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('nearest_town', Input::old('nearest_town'), array('class' => 'form-control')) !!}
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
                    {!! Form::label('address', Lang::choice('messages.address', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::textarea('address', Input::old('address'), 
                            array('class' => 'form-control', 'rows' => '3')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('in_charge', Lang::choice('messages.in-charge', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('in_charge', Input::old('in_charge'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                 <div class="form-group">
                    {!! Form::label('reporting_site', Lang::choice('messages.reporting-site', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('reporting_site', Input::old('reporting_site'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('operational_status', Lang::choice('messages.operational-status', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('operational_status', array('0' => 'Not Operational', '1' => 'Operational'),
                            old('status') ? old('status') : $status, 
                            array('class' => 'form-control', 'id' => 'operational_status')) !!}
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
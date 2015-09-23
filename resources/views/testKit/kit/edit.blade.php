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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.edit-test-kit', '1') }}</div>
    <div class="panel-body">
        <div class="col-lg-6 main">
            <!-- Begin form --> 
            @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
            @endif
            {!! Form::model($testKit, array('route' => array('testKit.update', $testKit->id), 
        'method' => 'PUT', 'id' => 'form-edit-testkit', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                                <div class="form-group">
                    {!! Form::label('full_name', Lang::choice('messages.full-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('full_name', Input::old('full_name'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('short_name', Lang::choice('messages.short-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('short_name', Input::old('short_name'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                   <div class="form-group">
                    {!! Form::label('manufacturer', Lang::choice('messages.manufacturer', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">  
                        {!! Form::text('manufacturer', Input::old('manufacturer'), array('class' => 'form-control')) !!}
                         </div>
                </div>
                             
                <div class="form-group">
                    {!! Form::label('approval_status', Lang::choice('messages.approval-status', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                         {!! Form::select('approval_status', array('0' => 'Not Approved', '1' => 'Approved',  '2' => 'Pending', '3' => 'Not Known'),'', 
                            array('class' => 'form-control', 'id' => 'approval_status')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('approval_agency_id', Lang::choice('messages.approval-agency', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('approval_agency', array(''=>trans('messages.select-approval-agency'))+$agencies,
                            old('agency') ? old('agency') : $agency, 
                            array('class' => 'form-control', 'id' => 'approval_agency')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('incountry_approval', Lang::choice('messages.incountry-approval', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                     {!! Form::select('incountry_approval', array('0' => 'No', '1' => 'Yes',  '2' => 'NA'),'', 
                            array('class' => 'form-control', 'id' => 'incountry_approval')) !!}
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
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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.create-new-test-kit', '1') }}</div>
    <div class="panel-body">
        <div class="col-lg-6 main">
            <!-- Begin form --> 
            @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
            @endif
            {!! Form::open(array('route' => 'testkit.store', 'id' => 'form-create-test-kit', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                <div class="form-group">
                    {!! Form::label('test-kit-name', Lang::choice('messages.test-kit-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('test-kit-name', Input::old('test-kit-name'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('kit-name', Lang::choice('messages.kit-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('kit-name', Input::old('kit-name'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                   <div class="form-group">
                    {!! Form::label('manufacturer', Lang::choice('messages.manufacturer', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">  
                        {!! Form::text('manufacturer', Input::old('manufacturer'), array('class' => 'form-control')) !!}
                         </div>
                </div>
                             
                <div class="form-group">
                    {!! Form::label('approval-status', Lang::choice('messages.approval-status', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('approval-status', array(''=>trans('messages.select-approval-status')),'', 
                            array('class' => 'form-control', 'id' => 'approval-status')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('approval-agency', Lang::choice('messages.approval-agency', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('approval-agency', array(''=>trans('messages.select-approval-agency')),'', 
                            array('class' => 'form-control', 'id' => 'approval-agency')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('incountry-approval', Lang::choice('messages.incountry-approval', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('incountry-approval', array(''=>trans('messages.select-incountry-approval')),'', 
                            array('class' => 'form-control', 'id' => 'incountry-approval')) !!}
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
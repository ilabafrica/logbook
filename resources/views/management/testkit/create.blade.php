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
            {!! Form::open(array('route' => 'managetestkit.store', 'id' => 'form-add-test-kit', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                <div class="form-group">
                    {!! Form::label('site_name', Lang::choice('messages.site-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                         {!! Form::select('site_name', array(''=>trans('messages.select-site-name')),'', 
                            array('class' => 'form-control', 'id' => 'site_name')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('kit_name', Lang::choice('messages.kit-name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                    {!! Form::select('kit_name', array(''=>trans('messages.select-kit-name')),'', 
                            array('class' => 'form-control', 'id' => 'kit_name')) !!}
                   </div>
                </div>
                   <div class="form-group">
                    {!! Form::label('lot_no', Lang::choice('messages.lot-no', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">  
                        {!! Form::text('lot_no', Input::old('lot_no'), array('class' => 'form-control')) !!}
                         </div>
                </div>
                 <div class="form-group">
                    {!! Form::label('expiry_date', Lang::choice('messages.expiry-date', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-offset-4 col-sm-7 input-group input-append date datepicker" id="date-of-birth" style="margin-left:170px;">
                        {!! Form::text('expiry_date', Input::old('expiry_date'), array('class' => 'form-control')) !!}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                 <div class="form-group">
                    {!! Form::label('comments', Lang::choice('messages.comments', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::textarea('comments', Input::old('comments'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('stock_avl', Lang::choice('messages.stock-avl', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('stock_avl', array(''=>trans('messages.stock-avl')),'', 
                            array('class' => 'form-control', 'id' => 'stock-avl')) !!}
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
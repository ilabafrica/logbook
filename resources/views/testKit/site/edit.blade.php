@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', '1') }}
            </li>
        </ol>
    </div>
</div>
@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.edit-site-kit', '1') }}</div>
    <div class="panel-body">
        <div class="col-lg-6 main">
            <!-- Begin form --> 
            @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
            @endif
            {!! Form::model($siteKit, array('route' => array('siteKit.update', $siteKit->id), 
                'method' => 'PUT', 'id' => 'form-edit-sitekit', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                <div class="form-group">
                    {!! Form::label('site_id', Lang::choice('messages.site', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('site', array(''=>trans('messages.select-site'))+$sites,
                            old('site') ? old('site') : $site, 
                            array('class' => 'form-control', 'id' => 'site')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('kit_id', Lang::choice('messages.kit', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                       {!! Form::select('kit', array(''=>trans('messages.select-test-kit'))+$kits,
                            old('kit') ? old('kit') : $kit, 
                            array('class' => 'form-control', 'id' => 'kit')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('lot-no', Lang::choice('messages.lot-no', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('lot_no', Input::old('lot_no'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('expiry-date', Lang::choice('messages.expiry-date', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        <div class=" input-group input-append date datepicker" id="expiry-date" >
                            {!! Form::text('expiry_date', Input::old('expiry_date'), array('class' => 'form-control')) !!}
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('comment', Lang::choice('messages.comment', 2), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::textarea('comments', Input::old('comments'), 
                            array('class' => 'form-control', 'rows' => '3')) !!}
                    </div>
                </div>                
                <div class="form-group">
                    {!! Form::label('stock-available', Lang::choice('messages.stock-available', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                     {!! Form::select('stock_available', array(''=>trans('messages.please-select'))+$stocks,
                            old('stock') ? old('stock') : $stock, 
                            array('class' => 'form-control', 'id' => 'stock_available')) !!}
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
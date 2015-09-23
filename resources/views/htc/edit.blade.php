@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.data-entry', 1) }}</a>
            </li>
        </ol>
    </div>
</div>

@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
   <div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> Logbook data entry for {!! $htc->site->name !!} at {!! $htc->site->facility->name !!} </div>
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
                {!! Form::model($htc, array('route' => array('htc.updateLogbook', $htc->id), 
        'method' => 'POST', 'id' => 'form-edit-htc', 'class' => 'form-horizontal')) !!}
                    <!-- CSRF Token -->
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <!-- ./ csrf token -->
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('site_id', Lang::choice('messages.site', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('site', $htc->site->name, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('book-no', Lang::choice('messages.book-no', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('book_no', Input::old('book_no'), array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('page-no', Lang::choice('messages.page-no', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('page_no', Input::old('page_no'), array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
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
                       <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('algorithm', Lang::choice('messages.algorithm', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('algorithm', array('1' => 'Serial', '2' =>'Parallel'),
                                    old('algorithm') ? old('algorithm') :$algorithm,
                                        array('class' => 'form-control', 'id' => 'algorithm')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    @foreach($testKits as $testKit)
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('s-kit-'.$testKit['id'], Lang::choice('messages.s-kit-'.$testKit['id'], 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('test_kit_'.$testKit['id'], array(''=>trans('messages.please-select'))+$skits,
                                        old('test_kit_'.$testKit['id']) ? old('test_kit_'.$testKit['id']) : $htc->htcData->first()->testKit($testKit['id'])->site_test_kit_id, 
                                        array('class' => 'form-control', 'id' => 'test_kit_'.$testKit['id'])) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    <div class="row">
                    @foreach($testKits as $testKit)
                    <?php
                        if($testKit['id'] == App\Models\Htc::TESTKIT1)
                            $color = 'green';
                        else if($testKit['id'] == App\Models\Htc::TESTKIT2)
                            $color = 'red';
                        else if($testKit['id'] == App\Models\Htc::TESTKIT3)
                            $color = 'yellow';
                    ?>
                        <div class="col-sm-4">
                            <div class="panel panel-{!! $color !!}">
                                <div class="panel-heading">
                                    {!! $testKit['name'] !!}
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <tbody>
                                                <tr>
                                                    <td><strong>{!! Lang::choice('messages.reactive', 1) !!}</strong></td>
                                                    <td><strong>{!! Lang::choice('messages.non-reactive', 1) !!}</strong></td>
                                                    <td><strong>{!! Lang::choice('messages.invalid', 1) !!}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            {!! Form::text('r_'.$testKit['id'], $htc->htcData->first()->testKit($testKit['id'])->reactive, array('class' => 'form-control')) !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            {!! Form::text('nr_'.$testKit['id'], $htc->htcData->first()->testKit($testKit['id'])->non_reactive, array('class' => 'form-control')) !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            {!! Form::text('inv_'.$testKit['id'], $htc->htcData->first()->testKit($testKit['id'])->invalid, array('class' => 'form-control')) !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('total-positive', Lang::choice('messages.total-positive', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('positive', Input::old('positive'), array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('total-negative', Lang::choice('messages.total-negative', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('negative', Input::old('negative'), array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('total-intermediate', Lang::choice('messages.total-intermediate', 1), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('indeterminate', Input::old('indeterminate'), array('class' => 'form-control')) !!}
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
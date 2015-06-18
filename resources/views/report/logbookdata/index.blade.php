@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> {{ Lang::choice('messages.logbook-data', 1) }}
            </li>
        </ol>
    </div>
</div>

    {!! Form::open(array('route' => 'logbookdata.store', 'id' => 'form-logbookdata', 'class' => 'form-horizontal')) !!}
    <div class="form-group">
        
                    {!! Form::label('report_start_date', Lang::choice('messages.report-start-date', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class=" col-sm-2 input-group input-append date datepicker" id="date-of-birth" style="margin-left:20px;">
                        {!! Form::text('report_start_date', Input::old('report_start_date'), array('class' => 'form-control')) !!}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                     {!! Form::label('report_end_date', Lang::choice('messages.report-end-date', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class=" col-sm-2 input-group input-append date datepicker" id="date-of-birth" style="margin-left:20px;">
                        {!! Form::text('report_end_date', Input::old('report_end_date'), array('class' => 'form-control')) !!}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
        <div class="form-group">
                    {!! Form::label('facility_id', Lang::choice('messages.facility', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-2">
                        {!! Form::select('facility', array(''=>trans('messages.select-facility'))+$facilities,'', 
                            array('class' => 'form-control', 'id' => 'facility')) !!}
                    </div>
                    {!! Form::label('testing_algorithm', Lang::choice('messages.testing-algorithm', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-2">  
                      {!! Form::select('testing_algorithm', array('serial' => 'Serial', 'parallel' => 'Parallel'),'', 
                            array('class' => 'form-control', 'id' => 'testing_algorithm'))  !!} 
                     </div>

        </div>
         <div class="form-group">
                   {!! Form::label('test_site', Lang::choice('messages.test-site', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-2">
                         {!! Form::select('site', array(''=>trans('messages.select-site'))+$sites,'', 
                            array('class' => 'form-control', 'id' => 'site')) !!}
                    </div>
                    {!! Form::label('overall_agreement', Lang::choice('messages.overall-agreement', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-2">
                       {!! Form::select('ratio', array('greaterthan' => '>=', 'lessthan' => '=<'),'', 
                            array('class' => 'form-control', 'id' => 'ratio'))  !!} </div>
                            <div class="col-sm-2">
                    {!! Form::text('overall_agreement', Input::old('overall_agreement'), array('class' => 'form-control')) !!}
                       
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
<br />

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.logbook-data', '1') }}</div>
    <div class="panel-body">
       <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            
                            <th rowspan="2">{{ Lang::choice('messages.algorithm', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.total-tests', 1) }}</th>
                            <th rowspan="1" colspan="3">{{ Lang::choice('messages.test1', 1) }}</th>
                            <th rowspan="1" colspan="3" >{{ Lang::choice('messages.test2', 1) }}</th>
                            <th rowspan="1" colspan="3">{{ Lang::choice('messages.test3', 1) }}</th> 
                            <th rowspan="2">{{ Lang::choice('messages.final-pos', 1) }}</th>                                                                           
                            <th rowspan="2">{{ Lang::choice('messages.%pos', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.positive-agr', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.overall-agr', 1) }}</th>
                            
                        </tr>
                         <tr>
                            <th>R </th>
                            <th> NR</th>
                            <th>Inv </th>
                             <th>R </th>
                            <th> NR</th>
                            <th>Inv </th>
                             <th>R </th>
                            <th> NR</th>
                            <th>Inv </th>
                           
                        </tr>
                    </thead>
                    <tbody>

                      
                        <tr>
                          <td colspan="3">{{ Lang::choice('messages.no-records-found', 1) }}</td>
                        </tr>
                       
                    </tbody>
                </table>
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
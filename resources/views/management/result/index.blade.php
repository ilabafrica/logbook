@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <a href="#"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
        </ol>
    </div>
</div>

{!! Form::open(array('route' => 'result.index', 'id' => 'form-result', 'class' => 'form-horizontal')) !!}
  
            <div class="form-group">
                    {!! Form::label('facility_id', Lang::choice('messages.facility-name', 1), array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-2">
                        {!! Form::select('facility', array(''=>trans('messages.select-facility'))+$facilities,'', 
                            array('class' => 'form-control', 'id' => 'facility')) !!}
                    </div>
                    {!! Form::button("<i class='glyphicon glyphicon-ok-circle'></i> ".Lang::choice('messages.submit', 1), 
                          array('class' => 'btn btn-success', 'onclick' => 'submit()')) !!}
                     </div>
         
 {!! Form::close() !!} 
<br />


@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.result-management', 2) }} 
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.facility-name', 1) }}</th>
                            <th>{{ Lang::choice('messages.site', 1) }}</th>
                            <th>{{ Lang::choice('messages.algo', 1) }}</th>
                            <th>{{ Lang::choice('messages.start-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.end-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.test1', 1) }}</th>
                             <th>{{ Lang::choice('messages.test2', 1) }}</th>
                              <th>{{ Lang::choice('messages.test3', 1) }}</th>
                                                       
                            <th></th>
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
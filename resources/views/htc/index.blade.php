@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <a href="#"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.data-entry', 1) }}</a>
            </li>
        </ol>
    </div>
</div>

@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
   <div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> Summary for {!! $facility->name !!} <span class="panel-btn">
      <a class="btn btn-sm btn-info" href="{!! url("htc/".$facility->id."/create") !!}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.data-entry') }}
          </a>
          <a class="btn btn-sm btn-info" href="{{ URL::to("import/facility") }}" >
            <span class="glyphicon glyphicon-download"></span>
                {{ trans('messages.import-data') }}
              </a>
        </span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover search-table">
                    <thead>
                        <tr>
                            <th rowspan="2">{{ Lang::choice('messages.site', 1) }}</th>                        
                            <th rowspan="2">{{ Lang::choice('messages.start-date', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.end-date', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.total-tests', 1) }}</th>
                            <th rowspan="1" colspan="3" class="success">{{ Lang::choice('messages.test1', 1) }}</th>
                            <th rowspan="1" colspan="3" class="danger">{{ Lang::choice('messages.test2', 1) }}</th>
                            <th rowspan="1" colspan="3" class="info">{{ Lang::choice('messages.test3', 1) }}</th>                                                                              
                            <th rowspan="2">{{ Lang::choice('messages.%pos', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.positive-agr', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.overall-agr', 1) }}</th>
                            <th rowspan="2"></th>                            
                        </tr>
                         <tr>
                            <td class="success">R</td>
                            <td class="success">NR</td>
                            <td class="success">Inv</td>
                            <td class="danger">R</td>
                            <td class="danger">NR</td>
                            <td class="danger">Inv </td>
                            <td class="info">R</td>
                            <td class="info">NR</td>
                            <td class="info">Inv</td>                           
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
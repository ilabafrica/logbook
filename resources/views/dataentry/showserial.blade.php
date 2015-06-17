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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.page-summary', '1') }}</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover ">
                    <thead>
                        <tr>
                            
                            <th>{{ Lang::choice('messages.site', 1) }}</th>                        
                            <th>{{ Lang::choice('messages.start-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.end-date', 1) }}</th>
                            <th>{{ Lang::choice('messages.total-tests', 1) }}</th>
                            <th >{{ Lang::choice('messages.test1R', 1) }}</th>
                            <th >{{ Lang::choice('messages.test1NR', 1) }}</th>
                            <th>{{ Lang::choice('messages.test1Inv', 1) }}</th>
                            <th >{{ Lang::choice('messages.test2R', 1) }}</th>
                            <th >{{ Lang::choice('messages.test2NR', 1) }}</th>
                            <th>{{ Lang::choice('messages.test2Inv', 1) }}</th>
                            <th >{{ Lang::choice('messages.test3R', 1) }}</th>
                            <th >{{ Lang::choice('messages.test3NR', 1) }}</th>
                            <th>{{ Lang::choice('messages.test3Inv', 1) }}</th>
                            <th>{{ Lang::choice('messages.%pos', 1) }}</th>
                            <th>{{ Lang::choice('messages.positive-agr', 1) }}</th>
                            <th>{{ Lang::choice('messages.overall-agr', 1) }}</th>
                            
                        </tr>
                    </thead>
                   
                </table>
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
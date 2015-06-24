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
                            @foreach($testKits as $testKit)
                                <?php
                                    if($testKit['id'] == App\Models\Htc::TESTKIT1)
                                        $class = 'success';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT2)
                                        $class = 'danger';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT3)
                                        $class = 'info';
                                ?>
                                <td class="{!! $class !!}">{!! Lang::choice('messages.reactive', 1) !!}</td>
                                <td class="{!! $class !!}">{!! Lang::choice('messages.non-reactive', 1) !!}</td>
                                <td class="{!! $class !!}">{!! Lang::choice('messages.invalid', 1) !!}</td>
                            @endforeach                         
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($sites as $site)
                        @foreach($site->htc as $htc)
                        <tr>
                            <td>{!! $site->name !!}</td>
                            <td>{!! $htc->start_date !!}</td>
                            <td>{!! $htc->end_date !!}</td>
                            <td>{!! $htc->positive+$htc->negative+$htc->indeterminate !!}</td>
                            @foreach($testKits as $testKit)
                                <?php
                                    if($testKit['id'] == App\Models\Htc::TESTKIT1)
                                        $class = 'success';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT2)
                                        $class = 'danger';
                                    else if($testKit['id'] == App\Models\Htc::TESTKIT3)
                                        $class = 'info';
                                ?>
                                <td class="{!! $class !!}">{!! $htc->htcData->first()->testKit($testKit['id'])->reactive !!}</td>
                                <td class="{!! $class !!}">{!! $htc->htcData->first()->testKit($testKit['id'])->non_reactive !!}</td>
                                <td class="{!! $class !!}">{!! $htc->htcData->first()->testKit($testKit['id'])->invalid !!}</td>
                            @endforeach
                            <td>{!! $htc->positivePercent() !!}</td>
                            <td>{!! $htc->positiveAgreement() !!}</td>
                            <td>{!! $htc->overallAgreement() !!}</td>
                            <td>
                                <a href="{!! url("htc/".$facility->id."/".$htc->id."/show") !!}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                                <a href="{!! url("htc/".$facility->id."/".$htc->id."/edit") !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ Session::put('SOURCE_URL', URL::full()) }}
        </div>
      </div>
</div>
@stop
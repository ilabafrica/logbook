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
                            <th rowspan="1" colspan="3" class="success">{{ Lang::choice('messages.test1', 1) }}</th>
                            <th rowspan="1" colspan="3" class="danger">{{ Lang::choice('messages.test2', 1) }}</th>
                            <th rowspan="1" colspan="3" class="info">{{ Lang::choice('messages.test3', 1) }}</th>
                            <th rowspan="2">{{ Lang::choice('messages.total-tests', 1) }}</th>                                                                              
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
                    @foreach($surveys as $survey)
                        @foreach($survey->sdps as $sdp)
                            @foreach($sdp->pages as $page)
                            <tr>
                                <td>{!! App\Models\SurveySdp::find($page->survey_sdp_id)->sdp->name !!}</td>
                                @foreach($page->questions as $question)
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'registerstartdate')
                                        <td>{!! $question->data->answer !!}</td>
                                    @endif
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'enddate')
                                        <td>{!! $question->data->answer !!}</td>
                                    @endif                                  
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'testreactive')
                                        <td class="success">{!! $question->data->answer !!}</td>
                                    @endif                                                                       
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'nonreactive')
                                        <td class="success">{!! $question->data->answer !!}</td>
                                    @endif                                   
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'totalinvalid')
                                        <td class="success">{!! $question->data->answer !!}</td>
                                    @endif                                    
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'testreactive1')
                                        <td class="danger">{!! $question->data->answer !!}</td>
                                    @endif                                                                       
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'nonreactive1')
                                        <td class="danger">{!! $question->data->answer !!}</td>
                                    @endif                                   
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'totalinvalid1')
                                        <td class="danger">{!! $question->data->answer !!}</td>
                                    @endif                                    
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'testreactive2')
                                        <td class="info">{!! $question->data->answer !!}</td>
                                    @endif                                                                       
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'nonreactive2')
                                        <td class="info">{!! $question->data->answer !!}</td>
                                    @endif                                   
                                    @if(App\Models\Question::find($question->question_id)->identifier === 'totalinvalid2')
                                        <td class="info">{!! $question->data->answer !!}</td>
                                    @endif
                                @endforeach
                                <td>{!! $page->totalTests() !!}</td>
                                <td>{!! $page->posPercent() !!}</td>
                                <td>{!! $page->posAgreement() !!}</td>
                                <td>{!! $page->overAgreement() !!}</td>
                                <td></td>
                            </tr>
                            @endforeach
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
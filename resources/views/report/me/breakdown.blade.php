@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.report', 2) }}</li>
        </ol>
    </div>
</div>
@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-tags"></i> {!! $checklist->name !!}
        <span class="panel-btn">
            <a class="btn btn-info btn-sm" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li><a href="{!! url('report/'.$checklist->id.'/me') !!}">{!! Lang::choice('messages.level-summary', 1) !!}</a></li>
            <li><a href="{!! url('analysis/chart') !!}">{!! Lang::choice('messages.stage-of-implementation', 1) !!}</a></li>
            <li><a href="{!! url('analysis/snapshot') !!}">{!! Lang::choice('messages.snapshot', 1) !!}</a></li>
            <li class="active"><a href="{!! url('analysis/breakdown') !!}">{!! Lang::choice('messages.domain-response-breakdown', 1) !!}</a></li>
            <li><a href="{!! url('analysis/response') !!}">{!! Lang::choice('messages.domain-response-chart', 1) !!}</a></li>
        </ul>
        <div class="container-fluid"> 
        {!! Form::open(array('url' => 'analysis/breakdown', 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) !!}
        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            <div class="row">
                @if(!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator')))
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label(trans('messages.select-county'), trans('messages.select-county'), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('county', array(''=>trans('messages.select-county'))+$counties, old($jimbo)?old($jimbo):$jimbo, 
                                array('class' => 'form-control', 'id' => 'county', 'onchange' => "dyn()")) !!}
                        </div>
                    </div>
                </div>
                @endif
                @if(Auth::user()->hasRole('County Lab Coordinator') || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label(Lang::choice('messages.sub-county', 1), Lang::choice('messages.sub-county', 1), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('sub_county', array(''=>trans('messages.select-sub-county'))+$subCounties, old($sub_county)?old($sub_county):$sub_county, 
                                array('class' => 'form-control', 'id' => 'sub_county', 'onchange' => "drop()")) !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <hr />
             <div class="row">
                @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label(Lang::choice('messages.facility', 1), Lang::choice('messages.facility', 1), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">

                            {!! Form::select('facility', array(''=>trans('messages.select-facility'))+$facilities, isset($site)?$site:'', 
                                array('class' => 'form-control', 'id' => 'facility', 'onchange' => "ssdp($checklist->id)")) !!}
                        </div>
                    </div>
                </div>
                @endif
                 @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label(Lang::choice('messages.sdp', 1), Lang::choice('messages.sdp', 1), array('class' => 'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('sdp', array(''=>trans('messages.select-sdp'))+$sdps, isset($sdp)?$sdp:'', 
                                array('class' => 'form-control', 'id' => 'sdp')) !!}

                        </div>
                    </div>
                </div>
                @endif
            </div>
            <hr />
            <div class="row">
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label('from', Lang::choice('messages.from', 1), array('class' => 'col-sm-4 control-label', 'style' => 'text-align:left')) !!}
                        <div class="col-sm-8 form-group input-group input-append date datepicker" style="padding-left:15px;">
                            {!! Form::text('from', isset($from)?$from:date('Y-m-01'), array('class' => 'form-control')) !!}
                            <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class='form-group'>
                        {!! Form::label('to', Lang::choice('messages.to', 1), array('class' => 'col-sm-4 control-label', 'style' => 'text-align:left')) !!}
                        <div class="col-sm-8 form-group input-group input-append date datepicker" style="padding-left:15px;">
                            {!! Form::text('to', isset($to)?$to:date('Y-m-d'), array('class' => 'form-control')) !!}
                            <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::button("<span class='glyphicon glyphicon-filter'></span> ".trans('messages.view'), 
                                array('class' => 'btn btn-danger', 'name' => 'view', 'id' => 'view', 'type' => 'submit')) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <hr />
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <?php $dates = ''; if($from==$to){ $dates = trans('messages.for-the-year').' '.date('Y');}else{ $dates = trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to; }  ?>
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <td colspan="5"><strong>{!! Lang::choice('messages.domain-responses', 1).$title.' '.$dates !!}</strong></td>
                                </tr>

                                <tr>
                                    <td>{{trans('messages.breakdown')}}</td>
                                    @foreach ($options as $option)
                                        <?php
                                            $class = '';
                                            $answer = $option;
                                            if($answer == 'Does Not Exist')
                                                $class = 'does-not-exist';
                                            else if($answer == 'In Development')
                                                $class = 'in-development';
                                            else if($answer == 'Being Implemented')
                                                $class = 'being-implemented';
                                            else if($answer == 'Completed')
                                                $class = 'completed';
                                        ?>
                                        <td class="{{$class}}">{!! $answer !!} ({!! App\Models\Answer::find(App\Models\Answer::idByName($answer))->score !!})</td>
                                    @endforeach
                                </tr>
                                @foreach($domain as $question_id)
                                {{--*/ $question = App\Models\Question::find($question_id) /*--}}
                                <tr>
                                    <td>{!! $question->description !!}</td>
                                    @foreach ($options as $option)
                                        <td>{!! '<strong>'.$question->breakdown($option, $sdp, $site, $sub_county, $jimbo, $from, $to).'</strong> ('.$question->domain($options, $option, $jimbo, $sub_county,  $site,  $sdp, $from, $to).'%)' !!}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@stop
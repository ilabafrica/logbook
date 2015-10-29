@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.summary', 1) }}</li>
        </ol>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
    <i class="fa fa-tags"></i> {!! $surveysdp->survey->checklist->name !!}
        <span class="panel-btn">
            <a class="btn btn-outline btn-primary btn-sm" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">{{ Lang::choice('messages.close', 1) }}</span></button>
                {!! session('message') !!}
            </div>
        @endif
        @if($errors->all())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">{!! Lang::choice('messages.close', 1) !!}</span></button>
            {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
        </div>
        @endif
        {!! Form::open(array('route' => array('survey.sdp.update', $surveysdp->id), 'method' => 'PUT', 'id' => 'form-edit-surveysdp', 'class' => 'form-horizontal')) !!}
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
            @foreach($surveysdp->survey->checklist->sections as $section)
                @if(($section->name != 'Total Score') && ($section->name != 'GPRS Location'))
                    <strong>{!! $section->name.' '.$section->label !!}</strong>
                    <hr />
                    @foreach($section->questions as $question)
                        <div class="row">
                            <div class="col-sm-6">
                                {!! $question->name !!}
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                    @if($question->question_type == App\Models\Question::CHOICE)
                                        @foreach($question->answers as $answer)
                                            <label class="radio-inline">{!! Form::radio('radio_'.$question->id, $answer->score, (($surveysdp->sq($question->id) && in_array($answer->score, [$surveysdp->sq($question->id)->sd->answer]))?true:false), ['class' => 'radio', 'id' => 'radio_'.$question->id]) !!}{!! $answer->name !!}</label>
                                        @endforeach
                                    @elseif($question->question_type == App\Models\Question::FIELD)
                                        @if($question->name == 'Name of the QA Officer')
                                            {!! Form::label('', $surveysdp->survey->qa_officer, array('class' => 'control-label text-primary')) !!}
                                        @else
                                            {!! Form::text('field_'.$question->id, $surveysdp->sq($question->id)?$surveysdp->sq($question->id)->sd->answer:'', ['class' => 'form-control']) !!}
                                        @endif
                                    @elseif($question->question_type == App\Models\Question::SELECT)
                                        @if($question->identifier == 'hh_testing_site')
                                            {!! Form::select('sdp', ['' => Lang::choice('messages.select', 1)]+$sdps, $surveysdp->sdp->id, ['class' => 'form-control']) !!}
                                        @elseif($question->identifier == 'audittype')
                                            {!! Form::select('select_'.$question->id, ['' => Lang::choice('messages.select', 1)]+array_change_key_case($audit_types, CASE_LOWER), ($surveysdp->sq($question->id) && $surveysdp->sq($question->id)->sd->answer)?$surveysdp->sq($question->id)->sd->answer:'', ['class' => 'form-control']) !!}
                                        @elseif($question->identifier == 'contirmatory' || $question->identifier == 'tiebreaker')
                                            {!! Form::select('select_'.$question->id, ['' => Lang::choice('messages.select', 1)]+array_change_key_case($test_kits, CASE_LOWER), ($surveysdp->sq($question->id) && $surveysdp->sq($question->id)->sd->answer)?$surveysdp->sq($question->id)->sd->answer:'', ['class' => 'form-control']) !!}
                                        @elseif($question->identifier == 'screen' && $surveysdp->survey->checklist->id == 2)
                                            {!! Form::select('select_'.$question->id, ['' => Lang::choice('messages.select', 1)]+array_change_key_case($test_kits, CASE_LOWER), ($surveysdp->sq(App\Models\Question::idById('screen')) && $surveysdp->sq(App\Models\Question::idById('screen'))->sd->answer)?$surveysdp->sq(App\Models\Question::idById('screen'))->sd->answer:'', ['class' => 'form-control']) !!}
                                        @elseif($question->identifier == 'algorithm')
                                            {!! Form::select('select_'.$question->id, ['' => Lang::choice('messages.select', 1)]+array_change_key_case($algorithms, CASE_LOWER), ($surveysdp->sq(App\Models\Question::idById('algorithm')) && $surveysdp->sq(App\Models\Question::idById('algorithm'))->sd->answer)?$surveysdp->sq(App\Models\Question::idById('algorithm'))->sd->answer:'', ['class' => 'form-control']) !!}
                                        @elseif($question->identifier == 'affiliation')
                                            {!! Form::select('select_'.$question->id, ['' => Lang::choice('messages.select', 1)]+array_change_key_case($affiliations, CASE_LOWER), ($surveysdp->sq($question->id) && $surveysdp->sq($question->id)->sd->answer)?$surveysdp->sq($question->id)->sd->answer:'', ['class' => 'form-control']) !!}
                                        @elseif($question->name == 'Facility')
                                            {!! Form::label('', $surveysdp->survey->facility->name, array('class' => 'control-label text-primary')) !!}
                                        @else
                                            {!! Form::select('select_'.$question->id, ['' => Lang::choice('messages.select', 1)], '', ['class' => 'form-control']) !!}
                                        @endif
                                    @elseif($question->question_type == App\Models\Question::TEXTAREA)
                                        {!! Form::textarea('textarea_'.$question->id, $surveysdp->sq($question->id)?$surveysdp->sq($question->id)->sd->answer:'', ['class' => 'form-control', 'rows' => '2']) !!}
                                    @elseif($question->question_type == App\Models\Question::DATE)
                                        <div class="input-group input-append date datepicker">
                                            {!! Form::text('field_'.$question->id, $surveysdp->sq($question->id)?$surveysdp->sq($question->id)->sd->answer:'', array('class' => 'form-control')) !!}
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    @elseif($question->question_type == App\Models\Question::MULTICHOICE)
                                        @if($question->identifier == 'cadres')
                                            @foreach($cadres as $cadre)
                                                <label class="checkbox-inline">{!! Form::checkbox('checkbox_'.$question->id.'[]', strtolower($cadre->name), (($surveysdp->sq($question->id) && in_array(strtolower($cadre->name), preg_split("/[\s,]+/", $surveysdp->sq($question->id)->sd->answer)))?true:false), ['class' => 'checkbox']) !!}{!! $cadre->name !!}</label>
                                            @endforeach
                                        @endif
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <hr />
                @endif
            @endforeach
            <div class="form-group">
                <div class="col-sm-offset-8 col-sm-4">
                {!! Form::button("<i class='glyphicon glyphicon-ok-circle'></i> ".Lang::choice('messages.save', 1), 
                    array('class' => 'btn btn-success', 'onclick' => 'submit()')) !!}
                {!! Form::button("<i class='glyphicon glyphicon-remove-circle'></i> ".'Reset', 
                    array('class' => 'btn btn-default', 'onclick' => 'reset()')) !!}
                <a href="#" class="btn btn-s-md btn-warning"><i class="glyphicon glyphicon-ban-circle"></i> {{ Lang::choice('messages.cancel', 1) }}</a>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
    <!-- /.panel-body -->
</div>
@stop
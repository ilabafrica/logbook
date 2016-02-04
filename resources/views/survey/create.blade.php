@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.survey', 1) }}</li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> 
        {{ Lang::choice('messages.fill-questionnaire', 1) }}
        <span class="panel-btn">
            <a class="btn btn-sm btn-info" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <!-- .panel-heading -->
    <div class="panel-body">
        <!-- Begin form --> 
        @if($errors->all())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">{!! Lang::choice('messages.close', 1) !!}</span></button>
            {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
        </div>
        @endif
        {!! Form::open(array('route' => 'survey.store', 'id' => 'form-add-survey', 'class' => 'form-horizontal')) !!}
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
            <!-- Hidden fields for audit_type_id -->
            {!! Form::hidden('checklist_id', $checklist->id, array('id' => 'checklist_id')) !!}
            @foreach($checklist->sections as $section)
                <legend><h5 class="text-primary"><strong>{!! $section->name !!}</strong></h5></legend>
                @foreach($section->questions as $question)
                    @if($question->question_type == App\Models\Question::CHOICE)
                        <div class='form-group'>
                            {!! Form::label('name', $question->name, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            <div class='col-sm-6'>
                            @foreach($question->answers as $response)
                                <label class='radio-inline'>{!! Form::radio('radio_'.$question->id, $response->name, false) !!}{!! $response->name !!}</label>
                            @endforeach
                            </div>
                        </div>
                    @elseif($question->question_type == App\Models\Question::DATE)
                        <div class='form-group'>
                            {!! Form::label('name', $question->name, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            <div class="col-sm-6 form-group input-group input-append date datepicker" style="padding-left:15px;">
                                {!! Form::text('date_'.$question->id, old('date_'.$question->id), array('class' => 'form-control')) !!}
                                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                            </div>
                        </div>
                    @elseif($question->question_type == App\Models\Question::FIELD)
                        <div class='form-group'>
                            {!! Form::label('name', $question->name, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            @if($question->id == App\Models\Question::idByName('Name of the QA Officer', $question->section->checklist->id))
                            <div class='col-sm-6'>
                                {!! Form::text('qa_officer', old('qa_officer'), array('class' => 'form-control')) !!}
                            </div>
                            @elseif($question->id == App\Models\Question::idByName('GPS Latitude', $question->section->checklist->id))
                            <div class='col-sm-6'>
                                {!! Form::text('latitude', old('latitude'), array('class' => 'form-control')) !!}
                            </div>
                            @elseif($question->id == App\Models\Question::idByName('GPS Longitude', $question->section->checklist->id))
                            <div class='col-sm-6'>
                                {!! Form::text('longitude', old('longitude'), array('class' => 'form-control')) !!}
                            </div>
                            @else
                            <div class='col-sm-6'>
                                {!! Form::text('textfield_'.$question->id, old('textfield_'.$question->id), array('class' => 'form-control')) !!}
                            </div>
                            @endif
                        </div>
                    @elseif($question->question_type == App\Models\Question::TEXTAREA)
                        <div class='form-group'>
                            {!! Form::label('name', $question->name, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            <div class='col-sm-6'>
                                @if($question->id == App\Models\Question::idByName('Additional Comments'))
                                    {!! Form::textarea('comments', old('comments'), 
                                        array('class' => 'form-control', 'rows' => '3')) !!}
                                @else
                                    {!! Form::textarea('textarea_'.$question->id, old('textarea_'.$question->id), 
                                        array('class' => 'form-control', 'rows' => '3')) !!}
                                @endif
                            </div>
                        </div>
                    @elseif($question->question_type == App\Models\Question::SELECT)
                        <div class="form-group">
                            {!! Form::label('select_'.$question->id, $question->name, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            <div class="col-sm-6">
                                @if($question->id == App\Models\Question::idByName('Facility', $question->section->checklist->id))
                                   {!! Form::select('facility', array(''=>trans('messages.select-facility'))+$facilities, '', 
                                array('class' => 'form-control', 'id' => 'facility', 'onchange' => "ssdp($checklist->id)")) !!}
                                @elseif($question->id == App\Models\Question::idByName('Service Delivery Points (SDP)', $question->section->checklist->id))
                                    {!! Form::select('fsdp', array(''=>trans('messages.select-sdp')), '', 
                                array('class' => 'form-control', 'id' => 'fsdp')) !!}
                                @endif
                                @if($question->section->checklist->id == App\Models\Checklist::idByName('SPI-RT Checklist'))
                                    @if($question->id == App\Models\Question::idByName('Affilliation (Circle One)', $question->section->checklist->id))
                                        {!! Form::select('affiliation', array(''=>trans('messages.select'))+$affiliations,'', 
                                        array('class' => 'form-control')) !!}
                                    @endif
                                @elseif($question->section->checklist->id == App\Models\Checklist::idByName('M & E Checklist'))
                                    @if($question->id == App\Models\Question::idByName('Type of Audit', $question->section->checklist->id))
                                        {!! Form::select('audit_type', array(''=>trans('messages.select'))+$auditTypes,'', 
                                        array('class' => 'form-control')) !!}
                                    @elseif($question->id == App\Models\Question::idByName('Current testing strategy used at the site (Serial vs. Parallel)', $question->section->checklist->id))
                                        {!! Form::select('algorithm', array(''=>trans('messages.select'))+$algorithms,'', 
                                        array('class' => 'form-control')) !!}
                                    @elseif($question->id == App\Models\Question::idByName('Screening or Test - 1:', $question->section->checklist->id))
                                        {!! Form::select('screening', array(''=>trans('messages.select'))+$kits,'', 
                                        array('class' => 'form-control')) !!}
                                    @elseif($question->id == App\Models\Question::idByName('Confirmatory or Test - 2:', $question->section->checklist->id))
                                        {!! Form::select('confirmatory', array(''=>trans('messages.select'))+$kits,'', 
                                        array('class' => 'form-control')) !!}
                                    @elseif($question->id == App\Models\Question::idByName('Tie-breaker or Test - 3 (if applicable):', $question->section->checklist->id))
                                        {!! Form::select('tie_breaker', array(''=>trans('messages.select'))+$kits,'', 
                                        array('class' => 'form-control')) !!}
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
                <hr>
            @endforeach
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
    <!-- .panel-body -->
</div>
@stop
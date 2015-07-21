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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.edit-questionnaire', 1) }}</div>
    <!-- .panel-heading -->
    <div class="panel-body">
        <!-- Begin form --> 
        @if($errors->all())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">{!! Lang::choice('messages.close', 1) !!}</span></button>
            {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
        </div>
        @endif
        {!! Form::open(array('route' => 'survey.edit', 'id' => 'form-add-survey', 'class' => 'form-horizontal')) !!}
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
                            @if($question->id == App\Models\Question::idByName('Name of the QA Officer'))
                            <div class='col-sm-6'>
                                {!! Form::text('qa_officer', old('qa_officer'), array('class' => 'form-control')) !!}
                            </div>
                            @elseif($question->id == App\Models\Question::idByName('GPS Latitude'))
                            <div class='col-sm-6'>
                                {!! Form::text('latitude', old('latitude'), array('class' => 'form-control')) !!}
                            </div>
                            @elseif($question->id == App\Models\Question::idByName('GPS Longitude'))
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
                                @if($question->id == App\Models\Question::idByName('Facility'))
                                   {!! Form::select('facility', array(''=>trans('messages.select'))+$facilities,'', 
                                    array('class' => 'form-control')) !!}
                                @elseif($question->id == App\Models\Question::idByName('Service Delivery Points (SDP)'))
                                    {!! Form::select('sdp', array(''=>trans('messages.select'))+$sdps,'', 
                                    array('class' => 'form-control')) !!}
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
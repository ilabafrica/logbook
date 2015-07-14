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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.fill-questionnaire', 1) }}</div>
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
            @foreach($checklist->sections as $section)
                <legend><h5><strong>{!! $section->name !!}</strong></h5></legend>
                @foreach($section->questions as $question)
                    @if($question->question_type == App\Models\Question::CHOICE)
                        <div class='form-group'>
                            {!! Form::label('name', $question->description, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            <div class='col-sm-6'>
                            @foreach($question->answers as $response)
                                <label class='radio-inline'>{!! Form::radio('radio_'.$question->id, $response->name, false) !!}{!! $response->name !!}</label>
                            @endforeach
                            </div>
                        </div>
                    @elseif($question->question_type == App\Models\Question::DATE)
                        <div class='form-group'>
                            {!! Form::label('name', $question->description, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            <div class="col-sm-6 form-group input-group input-append date datepicker" style="padding-left:15px;">
                                {!! Form::text('date_'.$question->id, old('date_'.$question->id), array('class' => 'form-control')) !!}
                                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                            </div>
                        </div>
                    @elseif($question->question_type == App\Models\Question::FIELD)
                        <div class='form-group'>
                            {!! Form::label('name', $question->description, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            <div class='col-sm-6'>
                                {!! Form::text('textfield_'.$question->id, old('textfield_'.$question->id), array('class' => 'form-control')) !!}
                            </div>
                        </div>
                    @elseif($question->question_type == App\Models\Question::TEXTAREA)
                        <div class='form-group'>
                            {!! Form::label('name', $question->description, array('class' => 'col-sm-6 control-label', 'style' => 'text-align:left')) !!}
                            <div class='col-sm-6'>
                                {!! Form::textarea('textarea_'.$question->id, old('textarea_'.$question->id), 
                                    array('class' => 'form-control', 'rows' => '3')) !!}
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        {!! Form::close() !!} 
        <!-- End form -->
    </div>
    <!-- .panel-body -->
</div>
@stop
@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li>
                <a href="{{ url('question') }}">{{ Lang::choice('messages.question', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.edit-question', 1) }}</li>
        </ol>
    </div>
</div>
@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.edit-question', '1') }}</div>
    <div class="panel-body">
        <div class="col-lg-10 main">
            <!-- Begin form --> 
            @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
            @endif
            {!! Form::model($question, array('route' => array('question.update', $question->id), 
        'method' => 'PUT', 'id' => 'form-edit-audit-type', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                <div class="form-group">
                    {!! Form::label('question-type', Lang::choice('messages.question-type', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('question_type', array(''=>trans('messages.select'))+$questionTypes,
                            old('questionType') ? old('questionType') : $questionType, 
                            array('class' => 'form-control', 'id' => 'question_type')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('name', Lang::choice('messages.name', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('name', Lang::choice('messages.description', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::textarea('description', Input::old('description'), 
                            array('class' => 'form-control', 'rows' => '3')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('section_id', Lang::choice('messages.section', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('section', array(''=>trans('messages.select'))+$sections,
                            old('section') ? old('section') : $section, 
                            array('class' => 'form-control', 'id' => 'section_id')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('score', Lang::choice('messages.score', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('score', Input::old('score'), array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('info', Lang::choice('messages.info', 1), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::textarea('info', Input::old('info'), 
                            array('class' => 'form-control', 'rows' => '3')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <label class="checkbox-inline">
                            {!! Form::checkbox("required", '1', '') !!}{{ Lang::choice('messages.required', 1) }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('answer', Lang::choice('messages.response', 2), array('class' => 'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php 
                                    $cnt = 0;
                                    $zebra = "";
                                ?>
                            @foreach($answers as $key=>$value)
                                {!! ($cnt%4==0)?"<div class='row $zebra'>":"" !!}
                                <?php
                                    $cnt++;
                                    $zebra = (((int)$cnt/4)%2==1?"row-striped":"");
                                ?>
                                <div class="col-md-3">
                                    <label  class="checkbox-inline">
                                        <input type="checkbox" name="answers[]" value="{{ $value->id}}" 
                                        {{ in_array($value->id, $question->answers->lists('id'))?"checked":"" }} />
                                        {{$value->name }}
                                    </label>
                                </div>
                                {!! ($cnt%4==0)?"</div>":"" !!}
                            @endforeach
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        
    </div>
</div>
@stop
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
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> 
        {!! $page->survey->facilitySdp->facility->name.':<strong>'.App\Models\FacilitySdp::cojoin($page->survey->facilitySdp->id).' for page '.$page->page.'</strong>' !!}
        <span class="panel-btn">
            <a href="{!! url('page/'.$page->id.'/download') !!}" class="btn btn-success" target=""><i class="fa fa-download"></i> {!! Lang::choice('messages.download-summary', 1) !!}</a>
        </span>
        <span class="panel-btn">
            <a class="btn btn-sm btn-info" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
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
        {!! Form::open(array('route' => array('survey.sdp.page.update', $page->id), 'method' => 'PUT', 'id' => 'form-edit-page', 'class' => 'form-horizontal')) !!}
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
            @foreach($page->survey->checklist->sections as $section)
                @if(($section->name != 'Total Score') && ($section->name != 'GPRS Location'))
                    <strong>{!! $section->name.' '.$section->label !!}</strong>
                    <hr />
                    @foreach($section->questions as $question)
                        @if($question->name != 'GPS Latitude' && $question->name != 'GPS Longitude' && $question->name != 'Additional Comments')
                        <div class="row">
                            <div class="col-sm-6">
                                {!! $question->name !!}
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                    @if($question->question_type == App\Models\Question::CHOICE)
                                        @foreach($question->answers as $answer)
                                            @if(($question->identifier == 'surpervisor') || ($question->identifier == 'algorithm' && $page->survey->checklist->id == 1))
                                                <label class="radio-inline">{!! Form::radio('radio_'.$question->id, $answer->score, (($page->sq($question->id) && in_array($answer->score, [$page->sq($question->id)->data->answer]))?true:false), ['class' => 'radio']) !!}{!! $answer->name !!}</label>
                                            @elseif($question->identifier == 'no')
                                                <label class="checkbox-inline">{!! Form::checkbox('checkbox_'.$question->id.'[]', $answer->description, (($page->sq($question->id) && in_array($answer->description, preg_split("/[\s,]+/", $page->sq($question->id)->data->answer)))?true:false), ['class' => 'checkbox']) !!}{!! $answer->name !!}</label>
                                            @else
                                                <label class="radio-inline">{!! Form::radio('radio_'.$question->id, strtolower(preg_replace("/\s+/", "", $answer->name)), (($page->sq($question->id) && in_array(strtolower(preg_replace("/\s+/", "", $answer->name)), [$page->sq($question->id)->data->answer]))?true:false), ['class' => 'radio']) !!}{!! $answer->name !!}</label>
                                            @endif
                                        @endforeach
                                    @elseif($question->question_type == App\Models\Question::FIELD)
                                        @if($question->name == 'Name of the QA Officer')
                                            {!! Form::label('', $page->survey->qa_officer, array('class' => 'control-label text-primary')) !!}
                                        @else
                                            {!! Form::text('field_'.$question->id, $page->sq($question->id)?$page->sq($question->id)->data->answer:'', ['class' => 'form-control']) !!}
                                        @endif
                                    @elseif($question->question_type == App\Models\Question::SELECT)
                                        @if($question->name == 'Service Delivery Points (SDP)')
                                            {!! Form::label('', App\Models\FacilitySdp::cojoin($page->survey->facilitySdp->id), array('class' => 'control-label text-primary')) !!}
                                        @elseif($question->name == 'Facility')
                                            {!! Form::label('', $page->survey->facilitySdp->facility->name, array('class' => 'control-label text-primary')) !!}
                                        @else
                                            {!! Form::select('select_'.$question->id, ['' => Lang::choice('messages.select', 1)], '', ['class' => 'form-control']) !!}
                                        @endif
                                    @elseif($question->question_type == App\Models\Question::TEXTAREA)
                                        {!! Form::textarea('textarea_'.$question->id, $page->sq($question->id)?$page->sq($question->id)->data->answer:'', ['class' => 'form-control', 'rows' => '2']) !!}
                                    @elseif($question->question_type == App\Models\Question::DATE)
                                        <div class="input-group input-append date datepicker">
                                            {!! Form::text('field_'.$question->id, $page->sq($question->id)?$page->sq($question->id)->data->answer:'', array('class' => 'form-control')) !!}
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
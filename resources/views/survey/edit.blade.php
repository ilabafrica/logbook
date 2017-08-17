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
        {{ Lang::choice('messages.edit-questionnaire', 1) }}
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
        {!! Form::model($survey, array('route' => array('survey.update', $survey->id), 
        'method' => 'PUT', 'id' => 'form-edit-survey', 'class' => 'form-horizontal')) !!}
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
            <div class="form-group">
                {!! Form::label('checklist', Lang::choice('messages.checklist', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('checklist', $survey->checklist->name, array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('start-time', Lang::choice('messages.start-time', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('start_time', $survey->date_started, array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('end-time', Lang::choice('messages.end-time', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('end_time', $survey->date_ended, array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('submit-time', Lang::choice('messages.submit-time', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('submission_time', $survey->date_submitted, array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('qa-officer', Lang::choice('messages.qa-officer', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('qa_officer', $survey->qa_officer, array('class' => 'form-control')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('facility_id', Lang::choice('messages.reporting-to-facility', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                   {!! Form::text('facility', $survey->facilitySdp->facility->name, array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('sdp_id', Lang::choice('messages.sdp', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                   {!! Form::text('sdp', $fsdp, array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class='form-group'>
                {!! Form::label('data-month', Lang::choice('messages.data-month', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8 form-group input-group input-append date datepicker" style="padding-left:15px;padding-right:15px;">
                    {!! Form::text('data_month', $survey->data_month, array('class' => 'form-control')) !!}
                    <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('sub-county', Lang::choice('messages.sub-county', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('sub_county', $survey->facilitySdp->facility->subCounty->name, array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('county', Lang::choice('messages.county', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('county', $survey->facilitySdp->facility->subCounty->county->name, array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('gps', Lang::choice('messages.gps', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('gps', (isset($survey->latitude) && isset($survey->longitude))?$survey->latitude.', '.$survey->longitude:'', array('class' => 'form-control', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('comments', Lang::choice('messages.comment', 1), array('class' => 'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('comments', isset($survey->comments)?$survey->comments:'', 
                        array('class' => 'form-control', 'rows' => '3')) !!}
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
    <!-- .panel-body -->
</div>
@stop
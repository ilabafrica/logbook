@extends('layout')

@section('content')
<!-- Page Content -->
<br />
<div class="container-fluid">
    {!! Form::open(array('url' => 'dashboard', 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) !!}
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
    {!! Form::close() !!}
    <hr />
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                {!! Lang::choice('messages.data-collection-summary', 1) !!}
            </div>
            <div class="panel-body">
                <div id="drill" style="height: 300px"></div>
            <hr />
            @foreach($checklists as $checklist)
                <div class="col-sm-4">
                    <div id="pie{{$checklist->id}}" style="height: 300px"></div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
<!-- /.container -->
<script src="{{ URL::asset('admin/js/highcharts.js') }}"></script>
<script src="{{ URL::asset('admin/js/highcharts-more.js') }}"></script>
<script src="{{ URL::asset('admin/js/exporting.js') }}"></script>
<script src="{{ URL::asset('admin/js/drilldown.js') }}"></script>
<script type="text/javascript">
    $(function () {
        $('#drill').highcharts(<?php echo $msline ?>);
        <?php
            foreach($checklists as $checklist)
            { ?>
                $('#pie<?php echo $checklist->id ?>').highcharts(<?php echo $pie[$checklist->id] ?>);
              <?php
            }
        ?>
    });
</script>
@endsection

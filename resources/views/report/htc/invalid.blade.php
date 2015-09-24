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
<div class="panel panel-default">
    <div class="panel-heading">
        {!! $checklist->name !!}
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li><a href="{!! url('report/'.$checklist->id) !!}">{!! Lang::choice('messages.percent-positive', 1) !!}</a></li>
            <li><a href="{!! url('report/'.$checklist->id.'/agreement') !!}">{!! Lang::choice('messages.percent-positiveAgr', 1) !!}</a></li>
            <li><a href="{!! url('report/'.$checklist->id.'/overall') !!}">{!! Lang::choice('messages.percent-overallAgr', 1) !!}</a></li>
            <li class="active" style="display:none;"><a href="{!! url('report/'.$checklist->id.'/invalid') !!}">{!! Lang::choice('messages.percent-invalidResult', 1) !!}</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            <div class="row">
                <div class="col-sm-12">
                    
                </div>
            </div>
          </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
<script src="{{ URL::asset('admin/js/highcharts.js') }}"></script>
<script src="{{ URL::asset('admin/js/highcharts-more.js') }}"></script>
<script src="{{ URL::asset('admin/js/exporting.js') }}"></script>
<script type="text/javascript">
    
</script>
@stop
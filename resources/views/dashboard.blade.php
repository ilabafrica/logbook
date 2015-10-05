@extends('layout')

@section('content')
<!-- Page Content -->
<br />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {!! Lang::choice('messages.completion-status', 1) !!}
                </div>
                <div class="panel-body">
                    <div id="drill" style="height: 300px"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6"> 
            <div class="panel panel-default">
                <div class="panel-heading">
                    {!! Lang::choice('messages.checklist-comparison', 1) !!}
                </div>
                <div class="panel-body">           
                    <div id="combination" style="height: 300px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                {!! Lang::choice('messages.data-collection-summary', 1) !!}
            </div>
            <div class="panel-body">
                <div class="col-sm-4">
                    <div id="htc" style="height: 300px"></div>
                </div>
                <div class="col-sm-4">
                    <div id="me" style="height: 300px"></div>
                </div>
                <div class="col-sm-4">
                    <div id="spirt" style="height: 300px"></div>
                </div>
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
        $('#drill').highcharts(<?php echo $drill ?>);
        $('#combination').highcharts(<?php echo $combination ?>);
        $('#htc').highcharts(<?php echo $htc_pie ?>);
        $('#me').highcharts(<?php echo $me_pie ?>);
        $('#spirt').highcharts(<?php echo $spi_pie ?>);
    });
</script>
@endsection

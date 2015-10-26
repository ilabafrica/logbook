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
@if(Session::has('message'))
<div class="alert alert-info">{{Session::get('message')}}</div>
@endif
<div class="panel panel-default">
    <div class="panel-heading">
    <i class="fa fa-tags"></i> {!! Lang::choice('messages.checklist-submit-comparison', 1) !!}
        <span class="panel-btn">
            <a class="btn btn-outline btn-primary btn-sm" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Tab panes -->
        <div class="container-fluid">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="{!! url('survey/overview') !!}">{!! Lang::choice('messages.summary', 1) !!}</a>
                </li>
                <li class=""><a href="{!! url('survey/me_spirt') !!}">{!! Lang::choice('messages.me-spirt', 1) !!}</a>
                </li>
                <li class=""><a href="{!! url('survey/me_htc') !!}">{!! Lang::choice('messages.me-htc', 1) !!}</a>
                </li>
                <li class=""><a href="{!! url('survey/htc_spirt') !!}">{!! Lang::choice('messages.htc-spirt', 1) !!}</a>
                </li>
            </ul>
            <div class="tab-content">
            <br />
            <p>
                <a href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}" class="btn btn-default"><i class="fa fa-chevron-left"></i> {!! Lang::choice('messages.back', 1) !!}</a>
                <a href="{!! url('survey/overview/download') !!}" class="btn btn-success" target=""><i class="fa fa-download"></i> {!! Lang::choice('messages.download-summary', 1) !!}</a>
            </p>
            <div class="row">                
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td>{!! 'M&E, SPIRT and HTC Lab Register' !!}</td>
                                <td>{!! $all !!}</td>
                            </tr>
                            <tr>
                                <td>{!! Lang::choice('messages.me-spirt', 1) !!}</td>
                                <td>{!! $spirt_me !!}</td>
                            </tr>
                            <tr>
                                <td>{!! Lang::choice('messages.me-htc', 1) !!}</td>
                                <td>{!! $htc_me !!}</td>
                            </tr>
                            <tr>
                                <td>{!! Lang::choice('messages.htc-spirt', 1) !!}</td>
                                <td>{!! $htc_spirt !!}</td>
                            </tr>
                            <tr>
                                <td>{!! 'PMTCT - SPIRT and M&E' !!}</td>
                                <td>{!! $pmtcts !!}</td>
                            </tr>
                            <tr>
                                <td>{!! 'PMTCT - HTC, SPIRT and M&E' !!}</td>
                                <td>{!! $pmtctMeSpi !!}</td>
                            </tr>
                            <tr>
                                <td>{!! Lang::choice('messages.complete', 1) !!}</td>
                                <td>{!! $all+($pmtcts-$pmtctMeSpi) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@stop
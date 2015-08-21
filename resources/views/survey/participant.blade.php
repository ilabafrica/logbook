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
        {!! $checklist->name !!}
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li><a href="{!! url('survey/'.$checklist->id.'/collection') !!}">{!! Lang::choice('messages.data-collection-summary', 1) !!}</a>
            </li>
            <li class=""><a href="{!! url('survey/'.$checklist->id.'/summary') !!}">{!! Lang::choice('messages.county-summary', 1) !!}</a>
            </li>
            <li class=""><a href="{!! url('survey/'.$checklist->id.'/subcounty') !!}">{!! Lang::choice('messages.sub-county-summary', 1) !!}</a>
            </li>
            <li class="active"><a href="{!! url('survey/'.$checklist->id.'/participant') !!}">{!! Lang::choice('messages.participants', 1) !!}</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            <p>
                <a href="#" class="btn btn-default"><i class="fa fa-chevron-left"></i> {!! Lang::choice('messages.back', 1) !!}</a>
                <a href="#" class="btn btn-success" target=""><i class="fa fa-download"></i> {!! Lang::choice('messages.download-summary', 1) !!}</a>
            </p>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover search-table">
                        <thead>
                            <tr>
                                <th>{{ Lang::choice('messages.count', 1) }}</th>
                                <th>{{ Lang::choice('messages.facility', 1) }}</th>
                                <th>{{ Lang::choice('messages.name', 1) }}</th>
                                <th>{{ Lang::choice('messages.number', 1) }}</th>
                            </tr>
                        </thead>
                         <tbody>
                         <?php $counter = 0; ?>
                         @foreach($facilities as $facility)
                         <?php $counter++; ?>
                            <tr>
                                <td>{!! $counter !!}</td>
                                <td>{!! $facility->code !!}</td>
                                <td>{!! $facility->name !!}</td>
                                <td>{!! $facility->surveys->count() !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@stop
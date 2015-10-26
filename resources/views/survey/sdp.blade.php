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
        <i class="fa fa-tags"></i> {!! $checklist->name !!}
        <span class="panel-btn">
            <a class="btn btn-outline btn-primary btn-sm" href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                <span class="glyphicon glyphicon-backward"></span> {{trans('messages.back')}}
            </a>
        </span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li><a href="{!! url('survey/'.$checklist->id.'/collection') !!}">{!! Lang::choice('messages.data-collection-summary', 1) !!}</a></li>
            @if(!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator')))
                <li class=""><a href="{!! url('survey/'.$checklist->id.'/county') !!}">{!! Lang::choice('messages.county-summary', 1) !!}</a></li>
            @endif
            @if(Auth::user()->hasRole('County Lab Coordinator') || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <li class=""><a href="{!! url('survey/'.$checklist->id.'/subcounty') !!}">{!! Lang::choice('messages.sub-county-summary', 1) !!}</a></li>
            @endif
            @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <li><a href="{!! url('survey/'.$checklist->id.'/participant') !!}">{!! Lang::choice('messages.participants', 1) !!}</a></li>
            @endif
            @if((Auth::user()->hasRole('County Lab Coordinator') || Auth::user()->hasRole('Sub-County Lab Coordinator')) || (!(Auth::user()->hasRole('County Lab Coordinator')) && !(Auth::user()->hasRole('Sub-County Lab Coordinator'))))
                <li class="active"><a href="{!! url('survey/'.$checklist->id.'/sdp') !!}">{!! Lang::choice('messages.sdp', 1) !!}</a></li>
            @endif
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            <p>
                <a href="#" onclick="window.history.back();return false;" alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}"><i class="fa fa-chevron-left"></i> {!! Lang::choice('messages.back', 1) !!}</a>
                <a href="{!! url('survey/'.$checklist->id.'/sdp/download') !!}" class="btn btn-success" target=""><i class="fa fa-download"></i> {!! Lang::choice('messages.download-summary', 1) !!}</a>
            </p>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{{ Lang::choice('messages.count', 1) }}</th>
                                <th>{{ Lang::choice('messages.facility', 1) }}</th>
                                <th>{{ Lang::choice('messages.sdp', 1) }}</th>
                                <th>{{ Lang::choice('messages.comment', 1) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 0; ?>
                        @foreach($facilities as $facility)
                            <?php $counter++; $total = 0; if($facility->sdps($checklist->id)!=0){ $total = $facility->sdps($checklist->id)+1; } ?>
                            <tr>
                                <td rowspan="{!! $total !!}">{!! $counter !!}</td>
                                <td rowspan="{!! $total !!}">{!! $facility->name !!}</td>
                            </tr>
                            @if(count($facility->perchecklist($checklist->id))!=0)
                                @foreach($facility->perchecklist($checklist->id) as $survey)
                                    @foreach($survey->sdps as $sdp)
                                    <tr>
                                        <td>{!! App\Models\Sdp::find($sdp->sdp_id)->name.'<br />' !!}</td>
                                        <td>{!! $sdp->comment.'<br />' !!}</td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            @endif
                        @endforeach
                        </tbody>
                     </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@stop
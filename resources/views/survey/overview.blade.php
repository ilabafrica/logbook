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
        {!! Lang::choice('messages.checklist-submit-comparison', 1) !!}
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
                <a href="javascript::history.back()" class="btn btn-default"><i class="fa fa-chevron-left"></i> {!! Lang::choice('messages.back', 1) !!}</a>
                <a href="{!! url('survey/overview/download') !!}" class="btn btn-success" target=""><i class="fa fa-download"></i> {!! Lang::choice('messages.download-summary', 1) !!}</a>
            </p>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{!! Lang::choice('messages.count', 1) !!}</th>
                                <th>{!! Lang::choice('messages.facility', 1) !!}</th>
                                @foreach($checklists as $checklist)
                                    <th>{{ $checklist->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 0; $span = 0; ?>
                            @foreach($facility_ids as $facility_id)
                            <?php $counter++; $facility = App\Models\Facility::find($facility_id); $me_c = App\Models\Checklist::find($me); $spirt_c = App\Models\Checklist::find($spi); $htc_c = App\Models\Checklist::find($htc);?>
                                <?php 
                                    if($me_c->ssdps(null, null, null, null, $facility_id, null) > $spirt_c->ssdps(null, null, null, null, $facility_id, null))
                                    {
                                        if($me_c->ssdps(null, null, null, null, $facility_id, null) > $htc_c->ssdps(null, null, null, null, $facility_id, null))
                                        {
                                            $span = $me_c->ssdps(null, null, null, null, $facility_id, null);
                                        }
                                        else
                                        {
                                            $span = $htc_c->ssdps(null, null, null, null, $facility_id, null);
                                        }
                                    }
                                    else
                                    {
                                        $span = $spirt_c->ssdps(null, null, null, null, $facility_id, null);
                                    }
                                ?>
                                <tr>
                                    <td rowspan="{{$span-1}}">{!! $span !!}</td>
                                    <th rowspan="{{$span-1}}">{!! $facility->name !!}</th>
                                </tr>                                
                                @foreach($htc_c->ssdps(null, null, null, null, $facility_id, 1) as $ssdp)
                                    <tr><td>{!! App\Models\Sdp::find($ssdp->sdp_id)->name !!}</td></tr>
                                @endforeach 
                                <?php 
                                    if((int)$htc_c->ssdps(null, null, null, null, $facility_id, null)<$span)
                                    {
                                        for($i=0; $i<($span-(int)$htc_c->ssdps(null, null, null, null, $facility_id, null)); $i++)
                                        {
                                            echo '<tr><td></td></tr>';
                                        }
                                    }
                                ?>
                                @foreach($me_c->ssdps(null, null, null, null, $facility_id, 1) as $ssdp)
                                    <tr><td>{!! App\Models\Sdp::find($ssdp->sdp_id)->name !!}</td></tr>
                                @endforeach
                                <?php 
                                    if((int)$me_c->ssdps(null, null, null, null, $facility_id, null)<$span)
                                    {
                                        for($i=0; $i<($span-(int)$me_c->ssdps(null, null, null, null, $facility_id, null)); $i++)
                                        {
                                            echo '<tr><td></td></tr>';
                                        }
                                    }
                                ?>
                                @foreach($spirt_c->ssdps(null, null, null, null, $facility_id, 1) as $ssdp)
                                    <tr><td>{!! App\Models\Sdp::find($ssdp->sdp_id)->name !!}</td></tr>
                                @endforeach
                                <?php 
                                    if((int)$spirt_c->ssdps(null, null, null, null, $facility_id, null)<$span)
                                    {
                                        for($i=0; $i<($span-(int)$spirt_c->ssdps(null, null, null, null, $facility_id, null)); $i++)
                                        {
                                            echo '<tr><td></td></tr>';
                                        }
                                    }
                                ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">                
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
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
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
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.count', 1) }}</th>
                            <th>{{ Lang::choice('messages.question', 1) }}</th>
                            <th>{{ Lang::choice('messages.response', 1) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $counter = 0; ?>
                    @foreach($page->questions as $question)
                        <?php $counter++; $qstn = App\Models\Question::find($question->question_id); ?>
                        <tr>
                            <td>{!! $counter !!}</td>
                            <td>{!! $qstn->name !!}</td>                                
                            <td>{!! App\Models\Answer::decode($qstn->question_type, $question->data->answer) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                 </table>
            </div>
        </div>
    </div>
</div>
@stop
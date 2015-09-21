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
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
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
                            <?php $counter++; ?>
                            <tr>
                                <td>{!! $counter !!}</td>
                                <td>{!! App\Models\Question::find($question->question_id)->name !!}</td>                                
                                <td>{!! (App\Models\Question::find($question->question_id)->question_type == App\Models\Question::CHOICE)?App\Models\Answer::find($question->data->answer+1)->name:$question->data->answer !!}</td>
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
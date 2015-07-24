@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li>
                <a href="{{ url('survey') }}">{{ Lang::choice('messages.survey', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.view', 1) }}</li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
  <div class="panel-heading"><i class="fa fa-tags"></i> {!! $survey->checklist->name !!} <span class="panel-btn">
  <a class="btn btn-sm btn-info" href="{!! url('survey/'.$survey->id."/". $checklist_id. "/edit") !!}" >
    <i class="fa fa-edit"></i><span> {{ trans('messages.edit-questionnaire') }}</span>
  </a>
  </span></div>
  <div class="panel-body">
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
              @foreach($survey->checklist->sections as $section)
                <tr>
                  <td colspan="3">{!! $section->name !!}</td>
                </tr>
                @foreach($section->questions as $question)
                <?php $counter++; ?>
                <tr>
                    <td>{!! $counter !!}</td>
                    <td>{!! $question->name !!}</td>
                    @if($question->id == App\Models\Question::idByName('Name of the QA Officer', $question->section->checklist->id))
                      <td>{!! $survey->qa_officer !!}</td>
                    @elseif($question->id == App\Models\Question::idByName('Facility', $question->section->checklist->id))
                      <td>{!! $survey->facility->name !!}</td>
                    @elseif($question->id == App\Models\Question::idByName('Service Delivery Points (SDP)' , $question->section->checklist->id))
                      <td>{!! $survey->sdp->name !!}</td>
                    @elseif($question->id == App\Models\Question::idByName('GPS Latitude', $question->section->checklist->id))
                      <td>{!! $survey->latitude !!}</td>
                    @elseif($question->id == App\Models\Question::idByName('GPS Longitude', $question->section->checklist->id))
                      <td>{!! $survey->longitude !!}</td>
                    @elseif($question->id == App\Models\Question::idByName('Additional Comments'))
                      <td>{!! $survey->comment !!}</td>
                    @else
                      <td>{!! $question->sq($survey->id)?$question->sq($survey->id)->sd->answer:'' !!}</td>
                    @endif
                </tr>
                @endforeach
              @endforeach
          </tbody>
      </table>
  </div>
</div>
@stop
@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.pt', 1) }}</li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {!! Lang::choice('messages.pt-program', 2) !!} <span class="panel-btn">
        @if(Auth::user()->can('create-pt'))
          <a class="btn btn-sm btn-info" href="{{ URL::to("pt/create") }}" >
          <span class="glyphicon glyphicon-plus-sign"></span>
            {{ trans('messages.create-pt') }}
          </a>
        @endif
        </span>
    </div>
    <div class="panel-body">
        @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">{{ Lang::choice('messages.close', 1) }}</span></button>
          {!! session('message') !!}
        </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover {!! !$pts->isEmpty()?'search-table':'' !!}">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.name', '1') }}</th>
                            <th>{{ Lang::choice('messages.description', '1') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pts as $pt)
                        <tr @if(session()->has('active_pt'))
                                {!! (session('active_pt') == $pt->id)?"class='warning'":"" !!}
                            @endif
                            >
                            <td>{{ $pt->name }}</td>
                            <td>{{ $pt->description }}</td>
                            <td>
                              <a href="{{ URL::to("pt/" . $pt->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> {{ Lang::choice('messages.view', 1) }}</span></a>
                             @if(Auth::user()->can('manage-pt'))
                              <a href="{{ URL::to("pt/" . $pt->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> {{ Lang::choice('messages.edit', 1) }}</span></a>
                              <!-- <a href="#" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>-->
                              @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="3">{{ Lang::choice('messages.no-records-found', 1) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! session(['SOURCE_URL' => URL::full()]) !!}
        </div>
      </div>
</div>
@stop
@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
            <li class="active">{{ Lang::choice('messages.permission', 1) }}</li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.permission', '2') }} <span class="panel-btn">
     @if(Auth::user()->can('create-permission'))
     <a class="btn btn-sm btn-info" href="{{ URL::to("permission/create") }}" >
        <span class="glyphicon glyphicon-plus-sign"></span>
          {{ Lang::choice('messages.create-permission', '1') }}
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
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.name', '1') }}</th>
                            <th>{{ Lang::choice('messages.description', '1') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                        <tr @if(session()->has('active_permission'))
                                {!! (session('active_permission') == $permission->id)?"class='warning'":"" !!}
                            @endif
                            >
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->display_name }}</td>
                            <td>
                              <a href="{{ URL::to("permission/" . $permission->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                              @if(Auth::user()->can('manage-permission'))
                              <a href="{{ URL::to("permission/" . $permission->id . "/edit") }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                            <!-- <a href="{{URL::to("permission/" . $permission->id . "/delete") }}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>-->
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
        </div>
      </div>
</div>
@stop
@extends("layout")
@section("content")
<br />
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
             <li class="active">
                <a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> {{ Lang::choice('messages.dashboard', 1) }}</a>
            </li>
        </ol>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.privilege', '2') }} 
        <span class="panel-btn">
            @if(Auth::user()->can('create-privilege'))
            <a class="btn btn-sm btn-info" href="{{ URL::to("role/create") }}" >
                <span class="glyphicon glyphicon-plus-sign"></span>
                {{ Lang::choice('messages.create-role', '1') }}
            </a>
            @endif
        </span>
        <span class="panel-btn">
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
                 {!! Form::open(array('route' => 'privilege.store', 'id' => 'form-add-privilege', 'class' => 'form-horizontal')) !!}
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.permission', 2) }}</th>
                            <th colspan="{{ count($roles)}}">{{ Lang::choice('messages.role', 2) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        @forelse($roles as $role)
                            <td>{{$role->name}}</td>
                        @empty
                            <td>{{trans('messages.no-roles-found')}}</td>
                        @endforelse
                    </tr>
                    @forelse($permissions as $permissionKey => $permission)
                        <tr>
                            <td>{{$permission->display_name}}</td>
                            @forelse($roles as $roleKey => $role)
                            <td>
                                @if($role == App\Models\Role::getAdminRole())
                                    <span class="glyphicon glyphicon-lock"></span>
                                    {!! Form::checkbox('permissionRoles['.$permissionKey.']['.$roleKey.']', '1',
                                    $permission->hasRole($role->name), array('style'=>'display:none') ) !!}
                                @else
                                    {!! Form::checkbox('permissionRoles['.$permissionKey.']['.$roleKey.']', '1',
                                    $permission->hasRole($role->name)) !!}
                                @endif
                                
                            </td>
                            @empty
                                <td>[-]</td>
                            @endforelse
                        </tr>
                    @empty
                    <tr><td colspan="{!! count($roles)+1 !!}">{{trans('messages.no-records-found')}}</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="form-group">
                    <div class="col-sm-offset-8">
                    {!! Form::button("<i class='glyphicon glyphicon-ok-circle'></i> ".Lang::choice('messages.save', 1), 
                          array('class' => 'btn btn-success', 'onclick' => 'submit()')) !!}
                          {!! Form::button("<i class='glyphicon glyphicon-remove-circle'></i> ".'Reset', 
                          array('class' => 'btn btn-default', 'onclick' => 'reset()')) !!}
                    <a href="#" class="btn btn-s-md btn-warning"><i class="glyphicon glyphicon-ban-circle"></i> {{ Lang::choice('messages.cancel', 1) }}</a>
                    </div>
                </div>
                   {!! Form::close() !!} 
            <!-- End form -->
            </div>

        </div>
      </div>
</div>
@stop
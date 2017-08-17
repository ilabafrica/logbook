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
    <div class="panel-heading"><i class="fa fa-tags"></i> {{ Lang::choice('messages.authorization', '2') }}
        <span class="panel-btn">
            @if(Auth::user()->can('create-user'))
            <a class="btn btn-sm btn-info" href="{{ URL::to("user/create") }}" >
                <span class="glyphicon glyphicon-plus-sign"></span>
                {{ Lang::choice('messages.create-user', '1') }}
            </a>
            @endif
        </span> 
        <span class="panel-btn">
            @if(Auth::user()->can('create-role'))
            <a class="btn btn-sm btn-info" href="{{ URL::to("role/create") }}" >
                <span class="glyphicon glyphicon-plus-sign"></span>
                {{ Lang::choice('messages.create-role', '1') }}
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
                {!! Form::open(array('route' => 'authorization.store', 'id' => 'form-add-authorization', 'class' => 'form-horizontal')) !!}
                <!-- CSRF Token -->
                <input type="hidden" id="_token" name="_token" value="{{{ csrf_token() }}}" />
                <!-- ./ csrf token -->
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ Lang::choice('messages.user',2) }}</th>
                            <th colspan="{{ count($roles)}}">{{ Lang::choice('messages.role',2) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            @forelse($roles as $role)
                                <td>{{$role->name}}</td>
                            @empty
                                <td>{{ trans('messages.no-roles-found')}}</td>
                            @endforelse
                        </tr>
                        @forelse($users as $userKey=>$user)
                            <tr>
                                <td>{{$user->name}}</td>
                                @forelse($roles as $roleKey=>$role)
                                <td>
                                    @if ($role == App\Models\Role::getAdminRole() && $user == App\Models\User::getAdminUser())
                                        <span class="glyphicon glyphicon-lock"></span>
                                        {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name),
                                        array('style'=>'display:none')) !!}
                                    @else
                                        @if($role->id == App\Models\Role::idByName('County Lab Coordinator'))
                                            {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name), array('onclick' => "county('$user->id')")) !!}
                                            @if($user->id != App\Models\User::getAdminUser()->id)
                                                <br />
                                                <div class="kaunti{!! $user->id !!}" <?php if(!$user->hasRole('County Lab Coordinator')){ ?>style="display:none" <?php } ?>>
                                                    <div class="form-group">
                                                        <div class="col-sm-8">
                                                            {!! Form::select('county'.$user->id, array(''=>trans('messages.select-county'))+$counties, ($user->tier && $user->hasRole('County Lab Coordinator'))?$user->tier->tier:'', 
                                                                array('class' => 'form-control', 'id' => 'county'.$user->id)) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @elseif($role->id == App\Models\Role::idByName('Sub-County Lab Coordinator'))
                                            {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name), array('onclick' => "sub_county('$user->id')")) !!}
                                            @if($user->id != App\Models\User::getAdminUser()->id)
                                                <br />
                                                    <div class="form-group sub_county{!! $user->id !!}" <?php if(!$user->hasRole('Sub-County Lab Coordinator')){ ?>style="display:none"<?php } ?>>
                                                        <div class="col-sm-8">
                                                            {!! Form::select('county_'.$user->id, array(''=>trans('messages.select-county'))+$counties, 
                                                                ($user->tier&& $user->hasRole('Sub-County Lab Coordinator'))?App\Models\SubCounty::find($user->tier->tier)->county->id:'', 
                                                                array('class' => 'form-control', 'id' => 'county_'.$user->id, 'onchange' => "load('$user->id')")) !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group sub_county{!! $user->id !!}" <?php if(!$user->hasRole('Sub-County Lab Coordinator')){ ?>style="display:none"<?php } ?>>
                                                        <div class="col-sm-8">
                                                            {!! Form::select('sub_county'.$user->id, array(''=>trans('messages.select-sub-county'))+$subCounties, 
                                                                ($user->tier&& $user->hasRole('Sub-County Lab Coordinator'))?$user->tier->tier:'', 
                                                                array('class' => 'form-control', 'id' => 'sub_county'.$user->id)) !!}
                                                        </div>
                                                    </div>
                                            @endif
                                        @else
                                            {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name)) !!}
                                        @endif
                                    @endif
                                </td>
                                @empty
                                    <td>[-]</td>
                                @endforelse
                            </tr>
                        @empty
                        <tr><td colspan="2">{{ trans('messages.no-users-found')}}</td></tr>
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
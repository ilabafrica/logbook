<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ Config::get('logbook.name') }}</title>

    <!-- Font awesome css -->
    <link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">

    <!-- Bootstrap Core CSS -->
    <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{ URL::asset('admin/css/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ URL::asset('admin/css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin/css/dataTables.bootstrap.css') }}" />
    <!-- Datepicker -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin/css/datepicker.css') }}" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery -->
    <script src="{{ URL::asset('admin/js/jquery.min.js') }}"></script>


</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">{{ Config::get('logbook.name') }}</a>
                <a class="navbar-brand" id="menu-toggle" href="#"><i class="fa fa-exchange"></i></a>
            </div>
            <!-- /.navbar-header -->
            <ul class="nav navbar-top-links navbar-left">
            <li class="dropdown">

                 <li class="dropdown">
                        <a href="" role="button" aria-expanded="false">
                        <span class="fa fa-clipboard"></span> {{ Lang::choice('messages.home', 2) }}
                    </a>
                    
                </li>
                   <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <span class="fa fa-stack-exchange"></span> {{ Lang::choice('messages.admin', 1) }}  <span class="caret"></span>
                    </a>
                   
                    <ul class="dropdown-menu">
                        
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.import-facility-data', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.import-test-kit', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.create-test-kit', 1) }}</a>
                        </li>
                    </ul>
                </li>

                 <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <span class="fa fa-stack-exchange"></span> {{ Lang::choice('messages.management', 1) }}  <span class="caret"></span>
                    </a>
                   
                    <ul class="dropdown-menu">
                        
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.facility-management', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.site-management', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.test-kit-management', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.result-management', 1) }}</a>
                        </li>

                    </ul>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <span class="fa fa-stack-exchange"></span> {{ Lang::choice('messages.data-entry', 1) }}  <span class="caret"></span>
                    </a>
                   
                    <ul class="dropdown-menu">
                        
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.page-summary-serial', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.page-summary-parallel', 1) }}</a>
                        </li>
                        
                    </ul>
                </li>

                 <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <span class="fa fa-stack-exchange"></span> {{ Lang::choice('messages.report', 1) }}  <span class="caret"></span>
                    </a>
                   
                    <ul class="dropdown-menu">
                        
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.logbook-data', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.trend-report', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.testkit-use', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.invalid-results', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href=""><span class="fa fa-tag"></span> {{ Lang::choice('messages.custom-report', 1) }}</a>
                        </li>

                    </ul>
                </li>

             </ul>   
              <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> {{ Auth::user()->name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{!! url('user/'.Auth::user()->id.'/edit') !!}"><span class="glyphicon glyphicon-user"></span> {{ Lang::choice('messages.user-profile', 1) }}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{!! url('/auth/logout') !!}"><span class="glyphicon glyphicon-log-out"></span> {{ Lang::choice('messages.sign-out', 1) }}</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
           
            <!-- /.navbar-top-links -->
<div class="navbar-default sidebar" role="navigation">

   @include("sidebar")
</div>
         <!-- /.navbar-static-side -->
        </nav>
        <div id="page-wrapper">
            @yield('content')
        <hr>
        <p>Copyright &copy; {{ date('Y') }} | <a href="http://www.ilabafrica.ac.ke">@iLabAfrica</a></p>
        </div>
    </div>
    <!-- /#wrapper -->
    
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ URL::asset('admin/js/bootstrap.min.js') }}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ URL::asset('admin/js/metisMenu.min.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ URL::asset('admin/js/sb-admin-2.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('admin/js/moment.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-datepicker.js') }}"></script>
</body>

</html>

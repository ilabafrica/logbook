<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ Config::get('logbook.name') }}</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Bootstrap -->
      <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
      <script src="{{ URL::asset('admin/js/jquery.min.js') }}"></script>
      <!-- Font awesome css -->
	  <link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">

      <style type="text/css">
          .header{
              padding-top: 45px;
              padding-bottom: 15px;
              border-bottom: 1px solid #e5e5e5;
          }
          .footer{
              margin-top: 45px;
              padding-top: 15px;
              padding-bottom: 15px;
              border-top: 1px solid #e5e5e5;
              text-align: center;
          }
          #slmta-tagline {
              padding-right: 60px;
              padding-left: 60px;
              padding-top: 48px;
              padding-bottom: 48px;
              text-align: center;
              color: #303C45;
          }
          #slmta-tagline h1 {
              font-size: 50px;
          }
          #slmta-tagline p {
              font-size: 18px;
          }
          a.brand:hover {
              text-decoration: none;
          }
          .navbar + .container{
              margin-top:3em;
          }
          .lft{
          		float: left;
          		width: 90px;
          		height: 90px;
          }
          .rght{
          		float: right;
          		width: 90px;
          		height: 90px;
          }
      	</style>
    </head>
    <body>
    	<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                
              </div>
              <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                  
                </ul>
              </div><!--/.nav-collapse -->
            </div>
        </nav>
      	<div class="container">
      	<br />
	      <div class="row">
	      <div class="col-lg-4 col-lg-offset-4">
	      	  <img src="{!! url('/images/coa.png') !!}" width="90px" height="90px" align="left">
	          <h3 class="text-center">HIV-QA Kenya</h3>
	            <p class="text-center">Sign in to get in touch</p>
	            <hr class="clean">
	          <form role="form" method="POST" action="{{ url('auth/login') }}">
	          <!-- CSRF Token -->
	          <input type="hidden" name="_token" value="{{ csrf_token() }}">
	          <!-- ./ csrf token -->
	              <div class="form-group input-group">
	                <span class="input-group-addon"><i class="fa fa-user"></i></span>
	                <input type="text" name="username" id="username" class="form-control"  placeholder="Username">
	              </div>
	              <div class="form-group input-group">
	                <span class="input-group-addon"><i class="fa fa-key"></i></span>
	                <input type="password" name="password" id="password" class="form-control"  placeholder="Password">
	              </div>
	              <div class="form-group">
	                <label class="cr-styled">
	                    <input type="checkbox" ng-model="todo.done">
	                    <i class="fa"></i> 
	                </label>
	                Remember me
	              </div>
	            <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
	            </form>
	            <hr>
	            
	            <p class="text-center text-gray">Can't access your account?</p>
	            <a class="btn btn-info btn-lg btn-block" href="{{ url('/password/email') }}">Reset your password.</a>
	        </div>
	        </div>
	        <footer class="footer">
              	<a href="http://www.ilabafrica.ac.ke">About @iLabAfrica</a> | 
              		{!! date('Y') !!} {!! Lang::choice('messages.compiled-by', 1) !!}
      	</footer>
	    </div>
      	<script src="{{ URL::asset('admin/js/bootstrap.min.js') }}"></script>
  	</body>
</html>
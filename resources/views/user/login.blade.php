<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<title>CTO :: {{ $page['title'] }}</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
	<div class="container-fluid margin-top--20">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row margin-top--30">
							<div class="col-xs-12 col-sm-5 col-md-4 margin-bottom--30">
								<figure class="col-xs-12 margin-bottom--20">
									<img src="{{ asset('assets/images/brand/logo_cto.jpeg') }}" alt="" class="img-responsive" style="margin: 0 auto">
									<figcaption class="text-muted text-center margin-top--10">@lang('general.system_name')</figcaption>
								</figure>
								
								@if (count($errors) > 0)
									@if ($errors->has('userNotFound'))
										<div class="col-sm-10 col-sm-offset-1">
											<div class="small alert alert-danger" role="alert">
												{{ $errors->first('userNotFound') }}
											</div>
										</div>
									@endif
								@endif
								
								<form id="loginForm" action="{{ url('/action/login') }}" method="post" class="col-sm-10 col-sm-offset-1">
									<div class="form-group {{ $errors->has('cto34_user') ? 'has-error' : '' }}">
										<label for="cto34_user">@lang('login.user')</label>
										<input  id="cto34_user"
												name="cto34_user"
												type="text"
												value="{{ old('cto34_user') }}"
											   	autocomplete="off"
												class="form-control input-sm" >
										@if ($errors->has('cto34_user'))
		                                    <span class="help-block text-danger">
		                                        <small class="text-danger">{{ $errors->first('cto34_user') }}</small>
		                                    </span>
		                                @endif
									</div>
									<div class="form-group {{ $errors->has('cto34_pass') ? 'has-error' : '' }}">
										<label for="cto34_pass">@lang('login.password')</label>
										<input id="cto34_pass" 
											name="cto34_pass" 
											type="password"
											class="form-control input-sm">
										@if ($errors->has('cto34_pass'))
		                                    <span class="help-block text-danger">
		                                        <small class="text-danger">{{ $errors->first('cto34_pass') }}</small>
		                                    </span>
		                                @endif
									</div>
									<div class="form-group">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<button id="loginSubmitButton" type="submit" class="btn btn-primary btn-sm btn-block">@lang('login.button_submit')</button>
									</div>
								</form>
								<div class="col-sm-10 col-sm-offset-1">
									<a href="" class="btn btn-link btn-sm padding-left--clear pull-left">@lang('login.forgot_password')</a>
									<button id="loginHelpButton" class="btn btn-link btn-sm pull-right padding-right--clear">
										<span class="fa fa-question-circle fa-fw fa-lg"></span>
									</button>
								</div>
							</div>
							<div class="col-description col-sm-7 col-md-8 text-center hidden-xs">
								<img src="{{ asset('assets/images/ui/resource_1.jpeg') }}" alt="" class="img-responsive" style="margin: 0 auto; margin-bottom: 10px">
								<img src="{{ asset('assets/images/ui/resource_2.jpeg') }}" alt="">
								<img src="{{ asset('assets/images/ui/resource_3.jpeg') }}" alt="">
								<img src="{{ asset('assets/images/ui/resource_4.jpeg') }}" alt="">
								<img src="{{ asset('assets/images/ui/resource_5.jpeg') }}" alt="">
								<p class="margin-top--10 text-muted text-center">@lang('general.system_description')</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="container-fluid">
		<div class="row margin-top--30">
			<div class="col-sm-12 text-center small">
				<p>&copy; {{ Carbon\Carbon::now()->year }} BFC Arquitectos S.C.</p>
				<p>
					<span class="fa fa-globe fa-fw"></span> 
					<a href="{{ url('language/es') }}">Español</a> / 
					<a href="{{ url('language/en') }}">English</a>
				</p>
			</div>
		</div>
	</footer>
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
    	(function() {
    		var app = new App();
    		app.loginHelpButton("@lang('login.help_message')");

    		$('#loginSubmitButton').on('click', function(event) {

    		    event.preventDefault();

    		    $(this).prop('disabled', true)
                    .html('<span class="fa fa-spinner fa-spin fa-fw"></span>');

    		    setTimeout(function () {

    		        $('#loginForm').submit();

                }, 1000)

            });

    	})();
    	
    </script>
</body>
</html>
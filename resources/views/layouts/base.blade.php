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
	@stack('styles_head')
	@yield('content_head')
</head>
<body>
	@section('navbar')
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="{{ url('panel') }}" class="navbar-brand">
						<img src="{{ asset('assets/images/brand/logo_cto.jpeg') }}" alt="">
					</a>
					<p class="navbar-text margin-left--clear small">
						CTO <span class="fa fa-caret-right fa-fw"></span>
						{{ $page['title'] }}
					</p>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown dropdown-work">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<span class="fa fa-cubes fa-fw"></span>
								Obras <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" style="width: 300px">
                                <?php $worksAll = App\Models\ConstructionWork::orderBy('tbObraID', 'DESC')->where('RegistroInactivo', '=', 0)->take(5)->get() ?>
                                <li class="dropdown-header">
                                    Activas ({{ App\Models\ConstructionWork::where('RegistroInactivo', '=', 0)->count() }})
                                </li>
                                @foreach($worksAll as $work)
                                    <li>
                                        <a href="{{ url('/panel/constructionwork') }}/info/{{ $work->tbObraID  }}">{{ $work->ObraAlias  }}</a>
                                    </li>
                                @endforeach
								<li role="separator" class="divider"></li>
                                <li>
                                    <a href="{{ url('/panel/constructionwork/home') }}" class="text-center"><span class="text-primary">Todas</span></a>
                                </li>
								@if (auth_permissions(Auth::user()['role'])->grant == 1)
									<li>
										<a href="{{ url('/panel/constructionwork/save') }}" class="btn btn-primary btn-primery" style="color: #fff">Nueva Obra</a>
									</li>
								@endif
							</ul>
						</li>
						@if ( auth_permissions(Auth::user()['role'])->grant == 1)
							<li class="dropdown dropdown-large">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<span class="fa fa-cog fa-fw"></span>
									Sistema <span class="caret"></span>
								</a>
								<ul class="dropdown-menu dropdown-menu-large row">
									<li class="col-sm-4">
										<ul>
											<li class="dropdown-header">
												Directorios
											</li>
											<li>
												<a href="{{ url('panel/system/business') }}">
													Empresas
												</a>
											</li>
											<!--<li>
												<a href="#">
													Libre
												</a>
											</li>-->
											<li>
												<a href="{{ url('panel/system/myBusiness') }}">
													Mis empresas
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/persons/info') }}">
													Personas
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/unities/info') }}">
													Unidades
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/users/info') }}">
													Usuarios
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/customers/info') }}">
													Clientes
												</a>
											</li>
										</ul>
									</li>
									<li class="col-sm-4">
										<ul>
											<li class="dropdown-header">
												Finanzas
											</li>
											<li>
												<a href="{{ url('panel/system/aplications/info') }}">
													Aplicaciones
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/items/info') }}">
													Rubros
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/currencies/info') }}">
													Monedas
												</a>
											</li>
										</ul>
									</li>
									<li class="col-sm-4">
										<ul>
											<li class="dropdown-header">
												Información
											</li>
											<li>
												<a href="{{ url('panel/system/logs') }}">
													Log
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/sessions') }}">
													Sesiones
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/arancel/info') }}">
													Arancel
												</a>
											</li>
											<li>
												<a href="#">
													Fechas
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/imss/info') }}">
													IMSS
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/groups/info') }}">
													Grupos
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/emails/info') }}">
													Correos
												</a>
											</li>
											<li>
												<a href="{{ url('panel/system/phones/info') }}">
													Teléfonos
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
						@endif
						@if (auth_permissions(Auth::user()['role'])->grant == 1)
						<li class="dropdown">
						<a href="#myModal" class="dropdown-toggle" role="button" id="businessDropdownMenu" data-toggle="modal">
								<span class="fa fa-building fa-fw"></span>
								Empresa 
							</a>
						</li>
						@endif
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" role="button" id="meDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="fa fa-user-circle fa-fw"></span>
								{{ Auth::user()['person']->PersonaNombres }} <span class="caret"></span>
							</a>
                            <ul class="dropdown-menu" aria-labelledby="meDropdownMenu">
                                <li>
                                    <a href="#">Perfil</a>
                                </li>
                                <li>
                                    <a href="{{ url('/action/logout')  }}">Cerrar sesión</a>
                                </li>
                            </ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
				<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Seleccione una empresa</h5>
      </div>
      <form action="{{ route('empresas') }}" method="post" >
      {{ csrf_field() }}
      <div class="modal-body">
      
      <select name="selectEmpresa" class="form-control" id="selectEmpresa">
			<option value="x" selected disabled>Seleccione una opción</option>

			@foreach(bfcFachada::listadoEmpresas() as $valores)
				<option value="{{$valores->tbDirEmpresaID}}">{{ $valores->EmpresaAlias }}</option>
			@endforeach
		</select>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" >Confirmar</button>
      </div>
      </form>
    </div>
  </div>
</div>
    @show
	@yield('content')
	<footer class="container-fluid">
		<div class="row margin-top--10">
			<div class="col-sm-12 text-center small">
				<p class="small">&copy; {{ Carbon\Carbon::now()->year }} BFC Arquitectos S.C.</p>
				<!--<p class="small">
					<span class="fa fa-globe fa-fw"></span>
					<a href="{{ url('language/es') }}">Español</a> /
					<a href="{{ url('language/en') }}">English</a>

				</p>-->
			</div>
		</div>
	</footer>
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script> 
	<script src="{{ asset('assets/js/helpers.js') }}"></script>
	<script src="{{ asset('assets/js/app.js') }}"></script>
	<script>
		setTimeout(function() {
            $('.alert').alert('close');
		}, 3000);
	</script>
	@stack('scripts_footer')
</body>
</html>
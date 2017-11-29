@extends('layouts.base')
@section('content')
<div>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Empresa: {{ $page['title'] }}</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Comunicaciones <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Carta poder</a></li>
            <li><a href="#">Envíos</a></li>
            <li><a href="#">Oficios</a></li>
          </ul>
        </li>

		<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Directorios <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Beneficiarios</a></li>
            <li><a href="#">Clientes</a></li>
            <li><a href="#">Empresas</a></li>
            <li><a href="#">Personas</a></li>
            <li><a href="#">Prestadores</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Equipos <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Equipos</a></li>
            <li><a href="#">Licencias de software</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Obras <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Obras</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Utilerias <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Asistencias</a></li>
            <li><a href="#">Pendientes</a></li>
          </ul>
        </li>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-4 col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #fff">
						Obras activas
					</div>
					@foreach ($works['all'] as $index => $work)
						<a href="{{ url('/panel/constructionwork') }}/info/{{ $work->TbObraID  }}" class="list-group-item">
							<h4 class="list-group-item-heading">{{ $work->ObraAlias }}</h4>
							<p class="text-muted small">
								{{ $work->ObraNombreCompleto }}
							</p>
						</a>
					@endforeach
				</div>
			</div>
			<div class="col-sm-8 col-md-9">
				<div class="panel panel-default">
					<div class="panel-body">
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-body">
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
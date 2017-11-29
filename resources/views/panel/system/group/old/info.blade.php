@extends('layouts.base')

@section('content')
	{{--
		Alertas
		Se mostraran las alertas que el sistema envíe
		si se redirecciona a index

		<alerts>
	--}}
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				@include('layouts.alerts', ['errors' => $errors])
			</div>
		</div>
	</div>
	{{-- </alerts> --}}
	{{--
		Menú de acciones

		Se muestra el título de la sección actual
		y los botones acción

		<navbar-actions>
	--}}
	<div class="navbar-actions">
		<div class="container">
			<div class="navbar navbar-static-top navbar-inverse margin-bottom--clear">
				<div class="container-fluid">
					<div class="navbar-header">
						<p class="navbar-text">
							{{ $page['title'] }} / {{ $groups['one']->DirGrupoNombre }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="{{ $navigation['base'] }}/save" class="is-tooltip" data-placement="bottom" title="Nuevo grupo">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
									</span>
								</a>
							</li>
							<li>
								<a href="#" data-placement="bottom" title="Filtrar"
								   data-toggle="modal" data-target="#modalFilter"
								   class="is-tooltip">
											<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-filter fa-stack-1x fa-inverse"></i>
									</span>
								</a>
							</li>
							@if (count($groups['all']) > 0)
								<li>
									<a href="{{ $navigation['base'] }}/update/{{ $groups['one']->tbDirGrupoID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="is-tooltip" data-placement="bottom" title="Editar">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
									</span>
									</a>
								</li>
								<li>
									<a href="#" id="confirmDeleteButton" class="is-tooltip" data-placement="bottom" title="Eliminar">
									<span class="fa-stack text-danger fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
									</span>
									</a>
								</li>
							@endif
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- </navbar-actions> --}}
	{{--

		Contenido de la sección

		Se mostra toda la lista de registro y la información de un registro
		seleccionado.

		<data-content>
	--}}
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section">
					@if (count($groups['all']) == 0)
						<div class="panel-body text-center">
							<h3>No hay empresas registradas</h3>
							<a href="{{ $navigation['base'] }}/save" class="btn btn-link btn-lg">Agregar empresa</a>
						</div>
					@else
						<div class="panel-body padding-clear">
							<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
								<div class="list-group-item">
									<form action="{{ $navigation['base'] }}/search" method="get">
										<div class="input-group">
											<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de la moneda">
											<span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
										    </span>
										</div>
									</form>
								</div>
								@foreach ($groups['all'] as $index => $group)
									<a href="{{ $navigation['base'] }}/info/{{ $group->tbDirGrupoID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item {{ ($group->tbDirGrupoID == $groups['one']->tbDirGrupoID) ? 'active' : '' }}">
										<h4 class="list-group-item-heading">{{ $group->DirGrupoNombre }}</h4>
										<p class="small {{ ($group->tbDirGrupoID == $groups['one']->tbDirGrupoID) ? '' : 'text-muted' }}">
											{{ Carbon\Carbon::parse($group->created_at )->formatLocalized('%d %B %Y') }}
										</p>
									</a>
								@endforeach
							</div>
							<div>
								<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
									{{--

										Formulario para eliminar un registro
										Si es index hay que sobre escribre la variable $model['one']
									--}}
									@include('panel.system.currency.shared.delete-form', [
										'title' => 'Moneda',
										'name' => $groups['one']->DirGrupoNombre,
										'id' => $groups['one']->tbDirGrupoID,
                                	])
									<h4 class="text-muted panel-section-title">Moneda</h4>
									<div class="col-sm-12 margin-bottom--20">
										<div class="col-sm-4">
											<label class="form-label-full">ID</label>
											<p class="helper-text">{{ $groups['one']->tbDirGrupoID }}</p>
										</div>
										<div class="col-sm-8">
											<label class="form-label-full">Nombre</label>
											<p class="helper-text">{{ $groups['one']->DirGrupoNombre }}</p>
										</div>
										<div class="col-sm-12">
											<label class="form-label-full">Descripción</label>
											<p class="helper-text">{{ $groups['one']->DirGrupoDescripcion }}</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endif
				</div>
				{{-- Se mostrara el menú de las página disponibles --}}
				{{ $navigation['pagination'] }}
			</div>
		</div>
	</div>
	{{-- </data-content> --}}
	{{-- Modal para filtrar --}}
	@include('panel.system.business.shared.filter-modal')
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
<script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
<script>
    (function() {

        var app = new App();
        app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
        app.tooltip();
        app.scrollNavActions();
        app.matchHeight('.panel-item');

    })();
</script>
@endpush
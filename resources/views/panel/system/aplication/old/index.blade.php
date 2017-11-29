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
							{{ $page['title'] }} {{ (count($aplications['all']) > 0) ? '/ '.$aplications['all'][0]->AplicacionAlias : '' }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="{{ $navigation['base'] }}/save" class="is-tooltip" data-placement="bottom" title="Nueva aplicacion">
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
							@if (count($aplications['all']) > 0)
							<li>
								<a href="{{ $navigation['base'] }}/update/{{ $aplications['all'][0]->tbRubroID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="is-tooltip" data-placement="bottom" title="Editar">
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
					@if (count($aplications['all']) == 0)
						<div class="panel-body text-center">
							<h3>No existen registros de aplicaciones</h3>
							<a href="{{ $navigation['base'] }}/save" class="btn btn-link btn-lg">Agregar aplicacion</a>
						</div>
					@else
						<div class="panel-body padding-clear">
							<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-aplication" style="position: relative;">
								<div class="list-group-aplication">
									<form action="{{ $navigation['base'] }}/search" method="get">
										<div class="input-group">
											<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de la aplicacion">
											<span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
										    </span>
										</div>
									</form>
								</div>
								@foreach ($aplications['all'] as $index => $b)

									@if ($index == 0)
										<a href="{{ $navigation['base'] }}/info/{{ $b->TbAplicacionID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-aplication active">
											<h4 class="list-group-aplication-heading">{{ $b->AplicacionAlias }}</h4>
											<p class="small">
												{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
											</p>
										</a>
										@continue
									@endif

									<a href="{{ $navigation['base'] }}/info/{{ $b->TbAplicacionID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-aplication">
										<h4 class="list-group-aplication-heading">{{ $b->AplicacionAlias }}</h4>
										<p class="text-muted small">
											{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
										</p>
									</a>
								@endforeach
							</div>
							<div>
								<div class="col-sm-7 col-md-8 col-lg-9 panel-aplication padding-left--clear padding-right--clear" style="position: relative;">
									{{-- 

										Formulario para eliminar un registro 
										Si es index hay que sobre escribre la variable $model['one']
									--}}
									@include('panel.system.aplication.shared.delete-form', ['aplications' => [ 'one' => $aplications['all'][0]]])
									<h4 class="text-muted panel-section-title">Rubros</h4>
									<div class="table-responsive">
										<table class="table table-info">
											<tbody>
												<tr>
													<td>
														<label>Aplicación</label>
														<p class="help-block">{{ $aplications['all'][0]->AplicacionAlias }}</p>
													</td>
													<td>
														<label>Cliente</label>
														<p class="help-block">{{ $aplications['all'][0]->tbClienteID_Aplicacion }}</p>
													</td>
													<td>
														<label>Obra</label>
														<p class="help-block">{{ $aplications['all'][0]->tbObraID_Aplicacion }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Contrato</label>
														<p class="help-block">{{ $aplications['all'][0]->AplicacionDescripcion }}</p>
													</td>
													<td>
														<label>Descripción</label>
														<p class="help-block">{{ $aplications['all'][0]->RegistroCerrado == 1?'Cerrado':'Inactivo' }}</p>
													</td>
												</tr>
												
											</tbody>
										</table>
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
	@include('panel.system.aplication.shared.filter-modal')
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
    		app.scrollNavActions();

    		$('.panel-aplication').matchHeight({byRow: true});
    		$(document).tooltip({selector: '.is-tooltip'});
    	})();
    </script>
@endpush
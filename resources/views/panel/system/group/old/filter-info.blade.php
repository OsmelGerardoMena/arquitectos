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
							{{ $page['title'] }} / {{ $filter['by'] }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="{{ $navigation['base'] }}/save" class="is-tooltip" data-placement="bottom" title="Nueva empresa">
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
							<li>
								<a href="{{ $navigation['base'] }}/update/{{ $business['one']->tbDirEmpresaID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'].'&by='.$filter['query'] : '?by='.$filter['query'] }}" class="is-tooltip" data-placement="bottom" title="Editar">
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
					@if (count($business['all']) == 0)
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
											<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de empresa" value="{{ $search['query'] }}">
											<span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
										    	@if ($navigation['search'])
										    		<a href="{{ $navigation['base'] }}" class="btn btn-link btn-sm">
														<div class="text-danger"><span class="fa fa-times fa-fw"></span></div>
													</a>
									    		@endif
										    </span>
										</div>
									</form>
								</div>
								@foreach ($business['all'] as $index => $b)

									@if ($index == 0)
										<a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item active">
											<h4 class="list-group-item-heading">{{ $b->EmpresaAlias }}</h4>
											<p class="small">
												{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
											</p>
										</a>
										@continue
									@endif

									<a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
										<h4 class="list-group-item-heading">{{ $b->EmpresaAlias }}</h4>
										<p class="text-muted small">
											{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
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
									@include('panel.system.business.shared.delete-form')
									<h4 class="text-muted panel-section-title">Empresa</h4>
									<div class="table-responsive">
										<table class="table table-info">
											<tbody>
												<tr>
													<td>
														<img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto">
													</td>
													<td colspan="2">
														<label>ID</label>
														<p class="help-block">{{ $business['one']->tbDirEmpresaID }}</p>
														<label>Alias</label>
														<p class="help-block">{{ $business['one']->EmpresaAlias }}</p>
														<label>Razón Social</label>
														<p class="help-block">{{ $business['one']->EmpresaRazonSocial }}</p>
														<label>Nombre Comercial</label>
														<p class="help-block">{{ $business['one']->EmpresaNombreComercial }}</p>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<hr>
													</td>
												</tr>
												<tr>
													<td>
														<label>Dependencia</label>
														<p class="help-block">{{ $business['one']->EmpresaDependencia }}</p>
													</td>
													<td>
														<label>Especialidad</label>
														<p class="help-block">{{ $business['one']->EmpresaEspecialidad }}</p>
													</td>
													<td>
														<label>Tipo de persona</label>
														<p class="help-block">{{ $business['one']->EmpresaTipoPersona }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Slogan</label>
														<p class="help-block">{{ $business['one']->EmpresaSlogan }}</p>
													</td>
													<td>
														<label>Página web</label>
														<p class="help-block">{{ $business['one']->EmpresaPaginaWeb }}</p>
													</td>
													<td>
														<label>RFC</label>
														<p class="help-block">{{ $business['one']->EmpresaRFC }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Número de IMSS</label>
														<p class="help-block">{{ $business['one']->EmpresaIMSSNumero }}</p>
													</td>
													<td>
														<label>Número de INFONAVIT</label>
														<p class="help-block">{{ $business['one']->EmpresaINFONAVITNumero }}</p>
													</td>
													<td>
														<label>RFC</label>
														<p class="help-block">{{ $business['one']->EmpresaSector }}</p>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<label>Comentarios</label>
														<p class="help-block">{{ $business['one']->EmpresaComentarios }}</p>
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
	@include('panel.system.business.shared.filter-modal')
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
    		app.scrollNavActions();

    		$('.panel-item').matchHeight({byRow: true});
    		$(document).tooltip({selector: '.is-tooltip'});
    	})();
    </script>
@endpush
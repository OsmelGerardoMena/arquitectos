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
							{{ $page['title'] }} {{ (count($mybusiness['all']) > 0) ? '/ '.$mybusiness['all'][0]->miEmpresaAlias : '' }}
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
							@if (count($mybusiness['all']) > 0)
							<li>
								<a href="{{ $navigation['base'] }}/update/{{ $mybusiness['all'][0]->TbmiEmpresaID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="is-tooltip" data-placement="bottom" title="Editar">
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
					@if (count($mybusiness['all']) == 0)
						<div class="panel-body text-center">
							<h3>No existen registros de empresas</h3>
							<a href="{{ $navigation['base'] }}/save" class="btn btn-link btn-lg">Agregar empresa</a>
						</div>
					@else
						<div class="panel-body padding-clear">
							<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
								<div class="list-group-item">
									<form action="{{ $navigation['base'] }}/search" method="get">
										<div class="input-group">
											<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de la empresa">
											<span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
										    </span>
										</div>
									</form>
								</div>
								@foreach ($mybusiness['all'] as $index => $b)

									@if ($index == 0)
										<a href="{{ $navigation['base'] }}/info/{{ $b->TbmiEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item active">
											<h4 class="list-group-item-heading">{{ $b->miEmpresaAlias }}</h4>
											<p class="small">
												{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
											</p>
										</a>
										@continue
									@endif

									<a href="{{ $navigation['base'] }}/info/{{ $b->TbmiEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
										<h4 class="list-group-item-heading">{{ $b->miEmpresaAlias }}</h4>
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
									@include('panel.system.mybusiness.shared.delete-form', ['mybusiness' => [ 'one' => $mybusiness['all'][0]]])
									<h4 class="text-muted panel-section-title">Mis Empresas</h4>
									<div class="table-responsive">
										<table class="table table-info">
											<tbody>
												<tr>
													<td>
														<label>Empresa Alia</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaAlias }}</p>
													</td>
													<td>
														<label>Empresa Id</label>
														<p class="help-block">{{ $mybusiness['all'][0]->TbDirEmrpresaID_miEmpresa }}</p>
													</td>
													<td>
														<label>Fecha de constitución</label>
														<p class="help-block">{{ Carbon\Carbon::parse($b->miEmpresaFechaConstitucion )->formatLocalized('%d %B %Y') }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Lugar de expedicion de factura</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaFacturaLugarDeExpedicion }}</p>
													</td>
													<td>
														<label>Cargo de representante legal</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaRepresentanteLegalCargo }}</p>
													</td>
													<td>
														<label>Cargo de representante legal</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaRepresentanteLegal2Cargo }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Slogan</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaSlogan }}</p>
													</td>
													<td>
														<label>Logotipo</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaLogotipo }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>No. de escritura constitutiva</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaEscrituraConstitutivaNo }}</p>
													</td>
													<td>
														<label>Fecha de escritura constitutiva</label>
														<p class="help-block">{{ Carbon\Carbon::parse($b->miEmpresaEscrituraConstitutivaFecha )->formatLocalized('%d %B %Y') }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Notaria de escritura constitutiva</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaEscrituraConstitutivaNotaria }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Notario de escritura constitutiva</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaEscrituraConstitutivaNotario }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Objeto social</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaObjetoSocial }}</p>
													</td>
													<td>
														<label>Objeto social 2</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaObjetoSocial1 }}</p>
													</td>
													<td>
														<label>Objeto social 3</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaObjetoSocial2 }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Socios Ypct</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaSociosYpct }}</p>
													</td>
													<td>
														<label>Libro de acta constitutiva</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaActaConstitutivaLibro }}</p>
													</td>
													<td>
														<label>Registro patronal IMSS</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaRegistroPatronalIMSS }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>No. de escritura Rppc</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaEscrituraRppcNo }}</p>
													</td>
													<td>
														<label>Fecha de escritura Rppc</label>
														<p class="help-block">{{ Carbon\Carbon::parse($b->miEmpresaEscrituraRppcFecha )->formatLocalized('%d %B %Y') }}</p>
													</td>
													<td>
														<label>Folio de escritura Rppc</label>
														<p class="help-block">{{ $mybusiness['all'][0]->miEmpresaEscrituraRPPCFolio }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Id registro fiscal </label>
														<p class="help-block">{{ $mybusiness['all'][0]->TbRegFiscalID_miEmpresa }}</p>
													</td>
													<td>
														<label>Id representante legal 1</label>
														<p class="help-block">{{ $mybusiness['all'][0]->TbDirPersonaID_RepresentanteLegal1 }}</p>
													</td>
													<td>
														<label>Id_ representante legal 2</label>
														<p class="help-block">{{ $mybusiness['all'][0]->TbDirPersonaID_RepresentanteLegal2 }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Id accionista 1 </label>
														<p class="help-block">{{ $mybusiness['all'][0]->TbDirPersonaID_Accionista1 }}</p>
													</td>
													<td>
														<label>Id accionista 2 </label>
														<p class="help-block">{{ $mybusiness['all'][0]->TbDirPersonaID_Accionista2 }}</p>
													</td>
													<td>
														<label>CTO ambito</label>
														<p class="help-block">{{ $mybusiness['all'][0]->tbCTOAmbito_miEmpresa }}</p>
													</td>
												</tr>
													<td>
														<label>Estatus</label>
														<p class="help-block">{{ $mybusiness['all'][0]->RegistroCerrado == 1?'Cerrado':'Inactivo' }}</p>
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
	@include('panel.system.mybusiness.shared.filter-modal')
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
@extends('layouts.base')

@section('content')
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
							{{ $page['title'] }} / {{ $business['all'][0]->ClienteDependencia }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li class="save">
								<a href="#" id="updateSubmitButton" class="is-tooltip" data-placement="bottom" title="Guardar">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
									</span>
								</a>
							</li>
							<li>
								<a href="{{ $navigation['base'] }}/save" class="is-tooltip confirm-leave" data-placement="bottom" title="Nueva empresa">
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
								<a href="#" id="confirmDeleteButton" class="is-tooltip" data-placement="bottom" title="Eliminar">
									<span class="fa-stack text-danger fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
									</span>
								</a>
							</li>
							<li>
								@if ($navigation['from'] == 'info')
									<a href="{{ $navigation['base'] }}/info/{{ $business['one']->TbClientesID }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
										<span class="fa-stack text-danger fa-lg">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-times fa-stack-1x fa-inverse"></i>
										</span>
									</a>
								@elseif ($navigation['from'] == 'search')
									<a href="{{ $navigation['base'] }}/search/info/{{ $business['one']->TbClientesID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
										<span class="fa-stack text-danger fa-lg">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-times fa-stack-1x fa-inverse"></i>
										</span>
									</a>
								@elseif ($navigation['from'] == 'filter')
									<a href="{{ $navigation['base'] }}/filter/info/{{ $business['one']->TbClientesID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
										<span class="fa-stack text-danger fa-lg">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-times fa-stack-1x fa-inverse"></i>
										</span>
									</a>
								@endif
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- </navbar-actions> --}}
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
					<div class="panel-body padding-clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item">
								<form action="{{ $navigation['base'] }}/search" method="get">
									<div class="input-group">
										<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de empresa" value="{{ ($navigation['search']) ? $search['query']: '' }}">
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
							{{-- 
								Lista de registros

								Muestra los registros como un menú permitiendo
								el acceso más rápido a la información.
								<data-list>
							--}}
							@foreach ($business['all'] as $index => $b)
								<a href="{{ $navigation['base'] }}/info/{{ $b->TbClientesID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item {{ ($b->TbClientesID == $business['one']->TbClientesID) ? 'active' : '' }}">
									<h4 class="list-group-item-heading">{{ $b->ClienteDependencia }}</h4>
									<p class="small {{ ($b->TbClientesID == $business['one']->TbClientesID) ? '' : 'text-muted' }}">
										{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
									</p>
								</a>
							@endforeach
							{{-- </data-list> --}}
						</div>
						<div>
							<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
								{{-- Formulario para eliminar un registro --}}
								@include('panel.system.customer.shared.delete-form', ['business' => $business])
								<h4 class="text-muted panel-section-title">Empresa</h4>
								@if (count($errors) > 0)
									<div class="col-sm-12">
										<div class="alert alert-danger" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  										<span aria-hidden="true">&times;</span>
											</button>
											{{ $errors->first() }}
										</div>
									</div>
								@endif
								@if(session()->has('success'))
									<div class="col-sm-12">
									    <div class="alert alert-success">
									    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			  									<span aria-hidden="true">&times;</span>
											</button>
									        {{ session()->get('success') }}
									    </div>
								    </div>
								@endif
								<form id="updateForm" action="{{ $navigation['base'] }}/action/update" method="POST" accept-charset="utf-8" class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-info">
											<tbody>
<!-- -->
										<tr>
											<td></td>
											<td></td>
											<td>
												<div class="form-group">
													<label for="cto34_alta" class="form-label-full">Fecha de alta</label>
													<input id="cto34_alta" 
														name="cto34_alta" 
														type="date" 
														value="{{ $business['one']->ClienteFechaAlta->format('Y-m-d') }}"
														class="form-control form-control-plain input-sm">
														<!-- $business['one']->ClienteFechaAlta  Carbon\Carbon::parse -->
												</div>
											</td>											
										</tr>
										<tr>
											<td colspan="3">
												<div class="form-group">
													<label for="cto34_business" class="form-label-full">Empresa</label>
													<input id="cto34_business" 
														name="cto34_business" 
														type="text" 
														value="{{ $business['one']->TbDirEmpresaID_Clientes }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>											
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_dependency" class="form-label-full">Dependencia</label>
													<input id="cto34_dependency" 
														name="cto34_dependency" 
														type="text" 
														value="{{ $business['one']->ClienteDependencia }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_sector" class="form-label-full">Sector</label>
													<select name="cto34_sector" class="form-control form-control-plain input-sm">
														<option value="1">Sector 1</option>
														<option value="2">Sector 2</option>
														<option value="2">Sector 3</option>
													</select>

												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_representative" class="form-label-full">Representante</label>
													<input id="cto34_representative" 
														name="cto34_representative" 
														type="text" 
														value="{{ $business['one']->tbDirPersonaEmpresaID_BitacoraDestinatario }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_cargo" class="form-label-full">Cargo</label>
													<input id="cto34_cargo" 
														name="cto34_cargo" 
														type="text" 
														value="{{ $business['one']->ClienteRepresentanteCargo }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_methodPay" class="form-label-full">Forma de pago</label>
													<select name="cto34_methodPay" class="form-control form-control-plain input-sm">
														<option {{ $business['one']->ClienteFormaDePago == 1?'selected':'' }} value="1">Targeta debito</option>
														<option {{ $business['one']->ClienteFormaDePago == 2?'selected':'' }} value="2">Tarjeta crédito</option>
													</select>
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_account" class="form-label-full">Cuenta</label>
													<input id="cto34_account" 
														name="cto34_account" 
														type="text" 
														value="{{ $business['one']->ClienteCuentaDePago }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_noProvider" class="form-label-full">Número de proveedor</label>
													<input id="cto34_noProvider" 
														name="cto34_noProvider" 
														type="text" 
														value="{{ $business['one']->noProvider }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<label for="cto34_status" class="form-label-full">Estatus</label>
												<p id="userStatusButton" class="help-block btn-toggle unselectable"><span class="fa fa-toggle-on fa-fw fa-2x fa-middle text-success"></span> Activo</p>
												<input id="cto34_status" name="cto34_status" type="hidden" value="1">
											</td>
										</tr>
<!-- -->
												<tr>
													<td colspan="3">
														<input type="hidden" name="cto34_id" value="{{ $business['one']->TbClientesID }}">
														<input type="hidden" name="_method" value="PUT">
														<input type="hidden" name="_token" value="{{ csrf_token() }}">
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				{{ $navigation['pagination'] }}
			</div>
		</div>
	</div>
	{{-- Modal para filtrar --}}
	@include('panel.system.customer.shared.filter-modal', ['navigation' => $navigation])
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
    		app.animateSubmit("updateForm", "updateSubmitButton");
    		app.scrollNavActions();

    		$('.panel-item').matchHeight({byRow: true});
    		$(document).tooltip({selector: '.is-tooltip'});
    	})();
    </script>
@endpush
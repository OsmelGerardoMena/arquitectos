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
							{{ $page['title'] }} / {{ $arancel['all'][0]->ArancelSupervision }}
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
								<a href="{{ $navigation['base'] }}/save" class="is-tooltip confirm-leave" data-placement="bottom" title="Nuevo arancel">
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
									<a href="{{ $navigation['base'] }}/info/{{ $arancel['one']->TbArancelID }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
										<span class="fa-stack text-danger fa-lg">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-times fa-stack-1x fa-inverse"></i>
										</span>
									</a>
								@elseif ($navigation['from'] == 'search')
									<a href="{{ $navigation['base'] }}/search/info/{{ $arancel['one']->TbArancelID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
										<span class="fa-stack text-danger fa-lg">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-times fa-stack-1x fa-inverse"></i>
										</span>
									</a>
								@elseif ($navigation['from'] == 'filter')
									<a href="{{ $navigation['base'] }}/filter/info/{{ $arancel['one']->TbArancelID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
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
										<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de el arancel" value="{{ ($navigation['search']) ? $search['query']: '' }}">
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
							@foreach ($arancel['all'] as $index => $b)
								<a href="{{ $navigation['base'] }}/info/{{ $b->TbArancelID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item {{ ($b->TbArancelID == $arancel['one']->TbArancelID) ? 'active' : '' }}">
									<h4 class="list-group-item-heading">{{ $b->ArancelSupervision }}</h4>
									<p class="small {{ ($b->TbArancelID == $arancel['one']->TbArancelID) ? '' : 'text-muted' }}">
										{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
									</p>
								</a>
							@endforeach
							{{-- </data-list> --}}
						</div>
						<div>
							<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
								{{-- Formulario para eliminar un registro --}}
								@include('panel.system.arancel.shared.delete-form', ['arancel' => $arancel])
								<h4 class="text-muted panel-section-title">Arancel</h4>
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
											<td>
												<div class="form-group">
													<label for="cto34_level" class="form-label-full">Nivel</label>
													<input id="cto34_level" 
														name="cto34_level" 
														type="text" 
														value="{{ $arancel['one']->ArancelNivel }}"
														placeholder="1"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_min" class="form-label-full">Minimo</label>
													<input id="cto34_min" 
														name="cto34_min" 
														type="text" 
														value="{{ $arancel['one']->ArancelMinimo }}"
														placeholder="1"
														class="form-control form-control-plain input-sm">

												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_med" class="form-label-full">Medio</label>
													<input id="cto34_med" 
														name="cto34_med" 
														type="text" 
														value="{{ $arancel['one']->ArancelMedio }}"
														placeholder="1"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td colspan="3">
												<div class="form-group">
													<label for="cto34_max" class="form-label-full">Maximo</label>
													<input id="cto34_max" 
														name="cto34_max" 
														type="text" 
														value="{{ $arancel['one']->ArancelMaximo }}"
														placeholder="1"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_supervision" class="form-label-full">Supervision</label>
													<input id="cto34_supervision" 
														name="cto34_supervision" 
														type="text" 
														value="{{ $arancel['one']->ArancelSupervision }}"
														placeholder="supervision"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_construction" class="form-label-full">Construccion</label>
													<input id="cto34_construction" 
														name="cto34_construction" 
														type="text" 
														value="{{ $arancel['one']->ArancelConstruccion }}"
														placeholder="construccion"
														class="form-control form-control-plain input-sm">

												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_study" class="form-label-full">Estudio y proyecto</label>
													<input id="cto34_study" 
														name="cto34_study" 
														type="text" 
														value="{{ $arancel['one']->ArancelEstudioYProyecto }}"
														placeholder="estudio y proyecto"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td colspan="3">
												<div class="form-group">
													<label for="cto34_scholarship" class="form-label-full">Escolaridad</label>
													<input id="cto34_scholarship" 
														name="cto34_scholarship" 
														type="text" 
														value="{{ $arancel['one']->ArancelEscolaridad }}"
														placeholder="escolaridad"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_sector" class="form-label-full">Estatus</label>
													<p id="userStatusButton" class="help-block btn-toggle unselectable"><span class="fa fa-toggle-{{ $arancel['one']->RegistroCerrado == 0?'off':'on' }} fa-fw fa-2x fa-middle text-{{ $arancel['one']->RegistroCerrado == 0?'danger':'success' }}"></span> {{ $arancel['one']->RegistroCerrado == 0?'Cerrado':'Activo' }}</p>
													<input id="cto34_status" name="cto34_status" type="hidden" value="{{ $arancel['one']->RegistroCerrado == 0?'0':'1' }}">
												</div>
											</td>
										</tr>
<!-- -->
												<tr>
													<td colspan="3">
														<input type="hidden" name="cto34_id" value="{{ $arancel['one']->TbArancelID }}">
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
	@include('panel.system.arancel.shared.filter-modal', ['navigation' => $navigation])
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
	<script src="{{ asset('assets/js/user.js') }}"></script>
	<script src="{{ asset('assets/js/person.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
    		app.animateSubmit("updateForm", "updateSubmitButton");
    		app.scrollNavActions();

    		$('.panel-item').matchHeight({byRow: true});
    		$(document).tooltip({selector: '.is-tooltip'});

    		var user = new User();
    		user.toggleButton('#userStatusButton', function(event) {

    			var status = document.getElementById('cto34_status');
    			var button = document.getElementById('userStatusButton');

    			if (status.value == 1) {
    				console.log('status active: ' + status.value);
    				button.innerHTML = '<span class="fa fa-toggle-off fa-fw fa-2x fa-middle text-danger"></span> Inactivo';
    				status.value = 0;
    				return;
    			}

    			if (status.value == 0) {
    				console.log('status inactive: ' + status.value);
    				button.innerHTML = '<span class="fa fa-toggle-on fa-fw fa-2x fa-middle text-success"></span> Cerrado';
    				status.value = 1;
    			}
    		});
    	})();
    </script>
@endpush
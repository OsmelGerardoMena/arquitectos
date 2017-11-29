@extends('layouts.base')

{{-- Se registran los archivos css requeridos para esta sección --}}
@push('styles_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
@endpush

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
							{{ $page['title'] }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li class="save">
								<a href="#" id="addSubmitButton" class="is-tooltip" data-placement="bottom" title="Guardar">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
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
								<a href="{{ $navigation['base'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
									<span class="fa-stack text-danger fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-times fa-stack-1x fa-inverse"></i>
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
				<div class="panel panel-default panel-section bg-white">
					<div class="panel-body padding-clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item">
								<form action="{{ $navigation['base'] }}/search" method="get">
									<div class="input-group">
										<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de el arancel">
										<span class="input-group-btn">
									    	<button class="btn btn-default" type="submit">
									    		<span class="fa fa-search fa-fw"></span>
									    	</button>
									    </span>
									</div>
								</form>
							</div>
						</div>
						<form id="saveForm" action="{{ $navigation['base'] }}/action/save" method="POST" accept-charset="utf-8" class="col-sm-7 col-md-8 col-lg-9 panel-item">
							<div class="table-responsive margin-top--10">
								<table class="table table-info">
									<tbody>
										<tr>
											{{--
												Alertas 
												Se mostraran las alertas que el sistema envíe
												si se redirecciona a index

												<alerts>
											--}}
											<td colspan="3">@include('layouts.alerts', ['errors' => $errors])</td>
											{{-- </alerts> --}}
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_level" class="form-label-full">Nivel</label>
													<input id="cto34_level" 
														name="cto34_level" 
														type="text" 
														value="{{ old('cto34_level') }}"
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
														value="{{ old('cto34_min') }}"
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
														value="{{ old('cto34_med') }}"
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
														value="{{ old('cto34_max') }}"
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
														value="{{ old('cto34_supervision') }}"
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
														value="{{ old('cto34_construction') }}"
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
														value="{{ old('cto34_study') }}"
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
														value="{{ old('cto34_scholarship') }}"
														placeholder="escolaridad"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_status" class="form-label-full">Estatus</label>
													<p id="userStatusButton" class="help-block btn-toggle unselectable"><span class="fa fa-toggle-on fa-fw fa-2x fa-middle text-success"></span> Activo</p>
													<input id="cto34_status" name="cto34_status" type="hidden" value="1">
												</div>
											</td>
										</tr>
										<tr>
										<tr>
											<td colspan="3">
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
	</div>
	{{-- </data-content> --}}
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
    		app.animateSubmit("saveForm", "addSubmitButton");
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
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
										<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de la empresa">
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
													<label for="cto34_nickname" class="form-label-full">Empresa Alias</label>
													<input id="cto34_nickname" 
														name="cto34_nickname" 
														type="text" 
														value="{{ old('cto34_nickname') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_id_dir" class="form-label-full">Empresa Id</label>
													<input id="cto34_id_dir" 
														name="cto34_id_dir" 
														type="text" 
														value="{{ old('cto34_id_dir') }}"
														placeholder="int"
														class="form-control form-control-plain input-sm">

												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_constitution_date" class="form-label-full">Fecha de constitución</label>
													<input id="cto34_constitution_date" 
														name="cto34_constitution_date" 
														type="date" 
														value="{{ old('cto34_constitution_date') }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_place" class="form-label-full">Lugar de expedicion de factura</label>
													<input id="cto34_place" 
														name="cto34_place" 
														type="text" 
														value="{{ old('cto34_place') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_cargo" class="form-label-full">Cargo de representante legal</label>
													<input id="cto34_cargo" 
														name="cto34_cargo" 
														type="text" 
														value="{{ old('cto34_cargo') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_cargo2" class="form-label-full">Cargo de representante legal</label>
													<input id="cto34_cargo2" 
														name="cto34_cargo2" 
														type="text" 
														value="{{ old('cto34_cargo2') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">

												</div>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<div class="form-group">
													<label for="cto34_slogan" class="form-label-full">Slogan</label>
													<input id="cto34_slogan" 
														name="cto34_slogan" 
														type="text" 
														value="{{ old('cto34_slogan') }}"
														placeholder="text" 
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td colspan="3">
												<div class="form-group">
													<label for="cto34_logo" class="form-label-full">Logotipo</label>
													<input id="cto34_logo" 
														name="cto34_logo" 
														type="text" 
														value="{{ old('cto34_logo') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<div class="form-group">
													<label for="cto34_legal_no" class="form-label-full">No. de escritura constitutiva</label>
													<input id="cto34_legal_no" 
														name="cto34_legal_no" 
														type="text" 
														value="{{ old('cto34_legal_no') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">

												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_legal_date" class="form-label-full">Fecha de escritura constitutiva</label>
													<input id="cto34_legal_date" 
														name="cto34_legal_date" 
														type="date" 
														value="{{ old('cto34_legal_date') }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>

										</tr>
										<tr>
											<td colspan="3">
												<div class="form-group">
													<label for="cto34_legal_f" class="form-label-full">Notaria de escritura constitutiva</label>
													<input id="cto34_legal_f" 
														name="cto34_legal_f" 
														type="text" 
														value="{{ old('cto34_legal_f') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>		
										</tr>
										<tr>
											<td colspan="3">
												<div class="form-group">
													<label for="cto34_legal_m" class="form-label-full">Notario de escritura constitutiva</label>
													<input id="cto34_legal_m" 
														name="cto34_legal_m" 
														type="text" 
														value="{{ old('cto34_legal_m') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>		
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_object_social_1" class="form-label-full">Objeto social</label>
													<input id="cto34_object_social_1" 
														name="cto34_object_social_1" 
														type="text" 
														value="{{ old('cto34_object_social_1') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_object_social_2" class="form-label-full">Objeto social 2</label>
													<input id="cto34_object_social_2" 
														name="cto34_object_social_2" 
														type="text" 
														value="{{ old('cto34_object_social_2') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_object_social_3" class="form-label-full">Objeto social 3</label>
													<input id="cto34_object_social_3" 
														name="cto34_object_social_3" 
														type="text" 
														value="{{ old('cto34_object_social_3') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_partners" class="form-label-full">Socios Ypct</label>
													<input id="cto34_partners" 
														name="cto34_partners" 
														type="text" 
														value="{{ old('cto34_partners') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_book" class="form-label-full">Libro de acta constitutiva</label>
													<input id="cto34_book" 
														name="cto34_book" 
														type="text" 
														value="{{ old('cto34_book') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_imss" class="form-label-full">Registro patronal IMSS</label>
													<input id="cto34_imss" 
														name="cto34_imss" 
														type="text" 
														value="{{ old('cto34_imss') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_no_rppc" class="form-label-full">No. de escritura Rppc </label>
													<input id="cto34_no_rppc" 
														name="cto34_no_rppc" 
														type="text" 
														value="{{ old('cto34_no_rppc') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_date_rppc" class="form-label-full">Fecha de escritura Rppc </label>
													<input id="cto34_date_rppc" 
														name="cto34_date_rppc" 
														type="date" 
														value="{{ old('cto34_date_rppc') }}"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_folio_rppc" class="form-label-full">Folio de escritura Rppc </label>
													<input id="cto34_folio_rppc" 
														name="cto34_folio_rppc" 
														type="text" 
														value="{{ old('cto34_folio_rppc') }}"
														placeholder="text"
														class="form-control form-control-plain input-sm">
												</div>
											</td>	
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_id_rf" class="form-label-full">Id registro fiscal </label>
													<input id="cto34_id_rf" 
														name="cto34_id_rf" 
														type="text" 
														value="{{ old('cto34_id_rf') }}"
														placeholder="int"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_id_representative_1" class="form-label-full">Id representante legal 1 </label>
													<input id="cto34_id_representative_1" 
														name="cto34_id_representative_1" 
														type="text" 
														value="{{ old('cto34_id_representative_1') }}"
														placeholder="int" 
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_id_representative_2" class="form-label-full">Id_ representante legal 2</label>
													<input id="cto34_id_representative_2" 
														name="cto34_id_representative_2" 
														type="text" 
														value="{{ old('cto34_id_representative_2') }}"
														placeholder="int"
														class="form-control form-control-plain input-sm">
												</div>
											</td>					
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label for="cto34_shareholder_1" class="form-label-full">Id accionista 1 </label>
													<input id="cto34_shareholder_1" 
														name="cto34_shareholder_1" 
														type="text" 
														value="{{ old('cto34_shareholder_1') }}"
														placeholder="int"
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_shareholder_2" class="form-label-full">Id accionista 2 </label>
													<input id="cto34_shareholder_2" 
														name="cto34_shareholder_2" 
														type="text" 
														value="{{ old('cto34_shareholder_2') }}"
														placeholder="int" 
														class="form-control form-control-plain input-sm">
												</div>
											</td>
											<td>
												<div class="form-group">
													<label for="cto34_cto" class="form-label-full">CTO ambito</label>
													<input id="cto34_cto" 
														name="cto34_cto" 
														type="text" 
														value="{{ old('cto34_cto') }}"
														placeholder="int"
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
	@include('panel.system.mybusiness.shared.filter-modal', ['navigation' => $navigation])
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
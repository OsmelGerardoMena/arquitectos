@extends('layouts.base')

@push('styles_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

{{--
    Content
    Cuerpo de la vista
--}}
@section('content')
	{{--
        Alertas
        Se mostraran las alertas que el sistema envíe
        si se redirecciona a index
    --}}
	@include('shared.alerts', ['errors' => $errors])
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
					<div class="col-sm-8"></div>
					<div class="col-sm-4">
						{{--
                            Nav Actions
                            Se incluyen los botones de acción para los registros

                        --}}
						@include('shared.nav-actions-save')
					</div>
					<div class="clearfix"></div>
					<div class="panel-body padding-top--clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item padding-clear padding-bottom--5">
								{{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
								@include('panel.system.phone.shared.content-search')
							</div>
							<div id="itemsList">
								<div class="list-group-item active">
									<h4 class="list-group-item-heading">
										Nuevo <span class="fa fa-caret-right fa-fw"></span>
									</h4>
									<p class="small">
										{{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}
									</p>
								</div>
								@foreach ($phones['all'] as $index => $phone)
                                    <a id="item{{ $phone->tbDirTelefonoID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $phone->tbDirTelefonoID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
			                            <h4 class="list-group-item-heading">
				                            {{ $phone->DirTelefonoNumero }} |
				                            {{ $phone->DirTelefonoTipo }}
			                            </h4>
			                            <p class="small">
			                                {{ Carbon\Carbon::parse($phone->created_at )->formatLocalized('%d %B %Y') }}
			                            </p>
                                    </a>
								@endforeach
							</div>
							<div class="list-group-item padding-clear padding-top--10">
								<div class="row">
									<div class="col-sm-12 text-center">
										{{--
                                            Pagination
                                            Se muestra datos y botones de paginación
                                         --}}
										@include('shared.pagination')
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
							<div class="col-sm-12 margin-bottom--5">
								<ul class="nav nav-tabs nav-tabs-works" role="tablist">
									<li role="presentation" class="active">
										<a href="#general" aria-controls="general" role="tab" data-toggle="tab">
											General
										</a>
									</li>
								</ul>
							</div>
							<div class="tab-content col-sm-12 margin-bottom--20">
								<div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
									<form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">

                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-6 col-md-10">
                                                <label for="cto34_business" class="form-label-full">Empresa</label>
                                                <select id="cto34_business"
                                                        name="cto34_business"
                                                        data-live-search="true"
                                                        data-width="100%"
                                                        data-style="btn-sm btn-default"
                                                        data-modal-title="Cliente directo"
                                                        class="selectpicker with-ajax">
                                                    @if(!empty(old('cto34_business')))
                                                        <option value="{{ old('cto34_business') }}" selected="selected">
                                                            {{ old('cto34_businessName')  }}
                                                        </option>
                                                    @endif
                                                </select>
                                                <input type="hidden" id="cto34_businessName" name="cto34_businessName" value="{{ old('cto34_businessName')  }}">
                                            </div> 
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-10">
                                                <label for="cto34_asigned" class="form-label-full">Persona</label>
                                                <select id="cto34_asigned"
                                                        name="cto34_asigned"
                                                        data-live-search="true"
                                                        data-width="100%"
                                                        data-style="btn-sm btn-default"
                                                        data-modal-title="Cliente directo"
                                                        class="selectpicker with-ajax">
                                                    @if(!empty(old('cto34_asigned')))
                                                        <option value="{{ old('cto34_asigned') }}" selected="selected">
                                                            {{ old('cto34_asignedName')  }}
                                                        </option>
                                                    @endif
                                                </select>
                                                <input type="hidden" id="cto34_asignedName" name="cto34_asignedName" value="{{ old('cto34_asignedName')  }}">
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-10">
                                                <label for="cto34_label" class="form-label-full">Etiqueta</label>
                                                <input id="cto34_label"
                                                       name="cto34_label"
                                                       type="text"
                                                       value="{{ old('cto34_label') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_phone_type" class="form-label-full">Teléfono tipo</label>
                                                <select name="cto34_phone_type" id="cto34_phone_type" class="form-control input-sm">
                                                    <option value="">Seleccionar opción</option>
                                                    <option value="Fijo">Fijo</option>
                                                    <option value="Celular">Celular</option>
                                                    <option value="Nextel">Nextel</option>
                                                </select>
                                            </div>

                                            
                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_area_code" class="form-label-full">Clave lada</label>
                                                <input id="cto34_area_code"
                                                       name="cto34_area_code"
                                                       type="text"
                                                       value="{{ old('cto34_area_code') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="clearfix"></div>


                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_phone_number" class="form-label-full">Número teléfonico</label>
                                                <input id="cto34_phone_number"
                                                       name="cto34_phone_number"
                                                       type="text"
                                                       value="{{ old('cto34_phone_number') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_ext" class="form-label-full">Extensión</label>
                                                <input id="cto34_ext"
                                                       name="cto34_ext"
                                                       type="text"
                                                       value="{{ old('cto34_ext') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_inactive" class="form-label-full">Inactivo</label>
                                                <input id="cto34_inactive"
                                                       name="cto34_inactive"
                                                       type="checkbox"
                                                       value="{{ old('cto34_inactive') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_closed" class="form-label-full">Cerrado</label>
                                                <input id="cto34_closed"
                                                       name="cto34_closed"
                                                       type="checkbox"
                                                       value="{{ old('cto34_closed') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>

										<input type="hidden" name="_token" value="{{ csrf_token() }}">
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- Guarda los errores para marcar los campos con errores --}}
	<input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
	{{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
	@include('panel.system.phone.shared.filter-modal')
@endsection
@push('scripts_footer')
	<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('assets/js/person.js') }}"></script>
    <script src="{{ asset('assets/js/business.js') }}"></script>
	<script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.tooltip();
            app.limitInput("#cto34_description", 4000);
            app.filterModal();

        })();

        // For search persons and business
            var person = new Person();

            person.searchWithAjax('#cto34_asigned',
            {
                url: '{{ url("ajax/search/persons") }}',
                token: '{{ csrf_token() }}',
                optionClass: 'option-newcto34_asigned',
                optionListClass: 'option-cto34_asigned',
                canAdd: false

            }, function(data) {

                if (data.action === 'newClicked') {
                    // add new person
                }

                if (data.action === 'optionClicked') {
                    var name = $(data.element).find('option:selected').text();
                    $(data.element + 'Name').val(name);
                }
            });

            var business = new Business();

            business.searchWithAjax('#cto34_business',
            {
                url: '{{ url("ajax/search/business") }}',
                token: '{{ csrf_token() }}',
                optionClass: 'option-newcto34_business',
                optionListClass: 'option-cto34_business',
                canAdd: false

            }, function(data) {

                if (data.action === 'newClicked') {
                    // add new person
                }

                if (data.action === 'optionClicked') {
                    var name = $(data.element).find('option:selected').text();
                    $(data.element + 'Name').val(name);
                    $(data.element).selectpicker({title: name})
                    .selectpicker('refresh');
                }
            });

	</script>
@endpush
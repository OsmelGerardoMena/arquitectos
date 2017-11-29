@extends('layouts.base')

{{-- Se registran los archivos css requeridos para esta sección --}}
@push('styles_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
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
						@include('shared.nav-actions-update', [ 'model' => [ 'id' => $customers['one']->getKey() ]])
					</div>
					<div class="clearfix"></div>
					<div class="panel-body padding-top--clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item padding-clear padding-bottom--5">
								{{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
								@include('panel.system.customer.shared.content-search')
							</div>
							<div id="itemsList">
								@foreach ($customers['all'] as $index => $customer)

									@if ($customer->TbClientesID == $customers['one']->TbClientesID)
										<div id="item{{ $customer->TbClientesID }}" class="list-group-item active">
											<h4 class="list-group-item-heading">{{ $customer->business->EmpresaRazonSocial }}</h4>
											<p class="small">
												{{ $customer->ClienteDependencia }}
											</p>
										</div>
										@continue
									@endif

									<a id="item{{ $customer->TbClientesID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $customer->TbClientesID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
										<h4 class="list-group-item-heading">{{ $customer->business->EmpresaRazonSocial }}</h4>
										<p class="text-muted small">
											{{ $customer->ClienteDependencia }}
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
									<form id="saveForm" action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8">
										<div class="col-sm-6 col-sm-offset-6">
											<label for="cto34_registeredAt" class="form-label-full">Fecha de registro</label>
											<input id="cto34_registeredAt"
												   name="cto34_registeredAt"
												   type="text"
												   readonly
												   value="{{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}"
												   class="form-control form-control-plain input-sm">
										</div>
										<div class="form-group col-sm-12">
											<label class="form-label-full">Empresa</label>
											<select id="cto34_business"
													name="cto34_business"
													data-live-search="true"
													data-width="100%"
													data-style="btn-sm btn-default"
													data-modal-title="Responsable en obra"
													class="selectpicker with-ajax">
													<option value="{{ $customers['one']->TbDirEmpresaID_Clientes }}" selected="selected">
														{{ $customers['one']->business->EmpresaRazonSocial  }}
													</option>
											</select>
											<input type="hidden" id="cto34_businessName" name="cto34_businessName" value="{{ $customers['one']->business->EmpresaRazonSocial }}">
										</div>
										<div class="form-group col-sm-6">
											<label for="cto34_dependency" class="form-label-full">Dependencia</label>
											<input id="cto34_dependency"
												   name="cto34_dependency"
												   type="text"
												   value="{{ ifempty($customers['one']->ClienteDependencia, '') }}"
												   class="form-control form-control-plain input-sm">
										</div>
										<div class="form-group col-sm-6">
											<label for="cto34_sector" class="form-label-full">Sector</label>
											<select name="cto34_sector" class="form-control form-control-plain input-sm">
												@if (!empty($customers['one']->ClienteSector))
													<optgroup label="Opción seleccionada">
														<option value="{{ $customers['one']->ClienteSector }}">{{ $customers['one']->ClienteSector }}</option>
													</optgroup>
												@endif
												<option value="">Seleccionar opción</option>
												@foreach(works_type_business_sector() as $option)
													<option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
												@endforeach
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-sm-6">
											<label class="form-label-full">Representante</label>
											<select id="cto34_searchPerson"
													name="cto34_searchPerson"
													data-live-search="true"
													data-width="100%"
													data-style="btn-sm btn-default"
													data-modal-title="Responsable en obra"
													class="selectpicker with-ajax">
                                                @if (!empty($customers['one']->person))
                                                    <option value="{{ $customers['one']->tbDirPersonaEmpresaID_BitacoraDestinatario }}" selected="selected">
                                                        {{ $customers['one']->person->PersonaNombreCompleto  }}
                                                    </option>
                                                @endif
											</select>
                                            @if (!empty($customers['one']->person))
                                                <input type="hidden" id="cto34_searchPersonName" name="cto34_searchPersonName" value="{{ $customers['one']->person->PersonaNombreCompleto  }}">
                                            @else
                                                <input type="hidden" id="cto34_searchPersonName" name="cto34_searchPersonName" value="{{ old('cto34_searchPersonName')  }}">
                                            @endif
										</div>
										<div class="form-group col-sm-6">
											<label for="cto34_job" class="form-label-full">Cargo</label>
											<input id="cto34_job"
												   name="cto34_job"
												   type="text"
												   value="{{ ifempty($customers['one']->ClienteRepresentanteCargo, '') }}"
												   class="form-control form-control-plain input-sm">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-sm-6">
											<label for="cto34_methodPay" class="form-label-full">Forma de pago</label>
											<select name="cto34_methodPay" class="form-control form-control-plain input-sm">
                                                @if (!empty($customers['one']->ClienteFormaDePago))
                                                    <optgroup label="Opción seleccionada">
                                                        <option value="{{ $customers['one']->ClienteFormaDePago }}">{{ $customers['one']->ClienteFormaDePago }}</option>
                                                    </optgroup>
                                                @endif
												<option value="">Seleccionar opción</option>
												<option value="Targeta debito">Targeta debito</option>
												<option value="Tarjeta crédito">Tarjeta crédito</option>
                                                <option value="Transferencia Electrónica">Transferencia Electrónica</option>
											</select>
										</div>
										<div class="form-group col-sm-6">
											<label for="cto34_account" class="form-label-full">Cuenta</label>
											<input id="cto34_account"
												   name="cto34_account"
												   type="text"
												   value="{{ ifempty($customers['one']->ClienteCuentaDePago, '') }}"
												   class="form-control form-control-plain input-sm">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-sm-6">
											<label for="cto34_supplierNumber" class="form-label-full">Número de proveedor</label>
											<input id="cto34_supplierNumber"
												   name="cto34_supplierNumber"
												   type="text"
												   value="{{ ifempty($customers['one']->ClienteProveedorNumero, '') }}"
												   class="form-control form-control-plain input-sm">
										</div>
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="put">
                                        <input type="hidden" name="_query" value="{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}">
                                        <input type="hidden" name="_hasSearch" value="{{ isset($filter['queries']['q']) ? 1 : 0 }}">
                                        <input type="hidden" name="cto34_id" value="{{ $customers['one']->getKey() }}">
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
	@include('panel.system.user.shared.filter-modal')
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

            var person = new Person();
            var business = new Business();

            /**
             * Hacemos busqueda general de empresas para nueva persona
             */
            person.searchWithAjax('#cto34_searchPerson',
                {
                    url: '{{ url("ajax/search/persons") }}',
                    token: '{{ csrf_token() }}',
                    optionClass: 'option-newSearchPerson',
                    optionListClass: 'option-searchPerson',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {

                    }

                    if (data.action === 'optionClicked') {
                        optionSelected(data.element);
                    }
                });

            business.searchWithAjax('#cto34_business',
                {
                    url: '{{ url("ajax/search/business") }}',
                    token: '{{ csrf_token() }}',
                    optionClass: 'option-newBusiness',
                    optionListClass: 'option-business',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {

                    }

                    if (data.action === 'optionClicked') {
                        optionSelected(data.element);
                    }
                });

        })();

        function optionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }
	</script>
@endpush
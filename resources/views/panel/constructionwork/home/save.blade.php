@extends('layouts.base')

@push('styles_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
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
					<div class="col-sm-8">
						<ul class="nav nav-pills nav-works">
							<li class="disabled">
								<a href="#">Inicio</a>
							</li>
							<li role="presentation" class="disabled">
								<a href="#">
									Coordinación
									<span class="caret"></span>
								</a>
							</li>
							<li role="presentation" class="disabled">
								<a href="#">
									Ubicación
									<span class="caret"></span>
								</a>
							</li>
							<li role="presentation" class="disabled">
								<a href="#">
									Directorios
									<span class="caret"></span>
								</a>
							</li>
							<li role="presentation" class="disabled">
								<a href="#">
									Finanzas
									<span class="caret"></span>
								</a>
							</li>
							<li role="presentation" class="disabled">
								<a href="#">
									Legal
									<span class="caret"></span>
								</a>
							</li>
						</ul>
					</div>
					<div class="col-sm-4">
                        <ul class="nav nav-actions navbar-nav navbar-right">
                            <li>
                                <a class="disabled">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#" id="addSubmitButton" class="is-tooltip" data-placement="bottom" title="Guardar">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="disabled">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="disabled">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $navigation['base'].'/home' }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
                                    <span class="fa-stack text-danger fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
					</div>
					<div class="clearfix"></div>
					<div class="panel-body padding-top--clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item padding-clear padding-bottom--5">
								{{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
								@include('panel.constructionwork.home.shared.content-search')
							</div>
							<div id="itemsList">
								<div class="list-group-item active">
									<h4 class="list-group-item-heading">
										Nuevo <span class="fa fa-caret-right fa-fw"></span>
									</h4>
									<p class="small">
										{{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}
									</p>
								</div>
								@foreach ($works['all'] as $index => $work)
									<a id="item{{ $work->tbObraID  }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $work->tbObraID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
										<h4 class="list-group-item-heading">{{ $work->ObraAlias }}</h4>
										<p class="text-muted small">
											{{ $work->ObraNombreCompleto }}
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
										<a href="#general" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
											General
										</a>
									</li>
									<li role="presentation">
										<a href="#dates" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
											Fechas
										</a>
									</li>
									<li role="presentation">
										<a href="#details" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
											Detalles
										</a>
									</li>
								</ul>
							</div>
							<form id="saveForm" action="{{ url('panel/constructionwork/action/save') }}" method="post" accept-charset="utf-8" class="tab-content col-sm-12 margin-bottom--20">
								{{ csrf_field() }}
								<div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
									<div class="col-sm-4">
										<div class="panel-item--image"><p>No disponible.</p></div>
										<input type="file" name="cto34_image" class="form-control input-sm">
									</div>
									<div class="form-group col-sm-8">
										<div class="row">
											<div class="form-group col-sm-12">
												<label class="form-label-full">Obra</label>
												<input id="cto34_alias"
													   name="cto34_alias"
													   type="text"
													   placeholder="Ínidice_Clave"
													   value="{{ old('cto34_alias') }}"
													   readonly
													   class="form-control form-control-plain input-sm">
											</div>
											<div class="form-group col-sm-6">
												<label class="form-label-full">Índice</label>
												<input id="cto34_index"
													   name="cto34_index"
													   type="text"
													   value="{{ old('cto34_index') }}"
													   class="form-control form-control-plain input-sm">
											</div>
											<div class="form-group col-sm-6">
												<label class="form-label-full">Clave</label>
												<input id="cto34_code"
													   name="cto34_code"
													   type="text"
													   value="{{ old('cto34_code') }}"
													   class="form-control form-control-plain input-sm">
											</div>
											<div class="form-group col-sm-12">
												<label for="cto34_owner" class="form-label-full">Propietario</label>
                                                <select id="cto34_owner"
                                                        name="cto34_owner"
                                                        data-live-search="true"
                                                        data-width="100%"
                                                        data-style="btn-sm btn-default"
                                                        data-modal-title="Contrato"
                                                        class="selectpicker with-ajax">
                                                    @if(!empty(old('cto34_owner')))
                                                        <option value="{{ old('cto34_owner') }}" selected="selected">
                                                            {{ old('cto34_ownerName')  }}
                                                        </option>
                                                    @endif
                                                </select>
                                                <input type="hidden" id="cto34_ownerName" name="cto34_ownerName" value="{{ old('cto34_ownerName')  }}">
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="form-group col-sm-12 margin-top--10">
										<label class="form-label-full">Nombre completo</label>
										<input id="cto34_fullName"
											   name="cto34_fullName"
											   type="text"
											   value="{{ old('cto34_fullName') }}"
											   class="form-control form-control-plain input-sm">
									</div>
                                    <div class="form-group col-sm-8">
                                        <label class="form-label-full">Descripción completa</label>
                                        <textarea maxlength="4000" name="cto34_description" id="cto34_description" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
									<div class="form-group col-sm-4">
										<label class="form-label-full">Descripción corta</label>
										<textarea name="cto34_description" id="cto34_description" cols="30" rows="3" class="form-control" disabled></textarea>
									</div>
									<div class="form-group col-sm-12">
										<label class="form-label-full">Sucursal </label>
										<input id="cto34_branch"
											   name="cto34_branch"
											   type="text"
											   value="{{ old('cto34_branch') }}"
											   class="form-control form-control-plain input-sm">
									</div>
                                    <div class="form-group col-sm-12">
                                        <label for="cto34_address" class="form-label-full">Domicilio</label>
                                        <select name="cto34_address" id="cto34_address" class="form-control" disabled>
                                            <option value="">Seleccionar opción</option>
                                        </select>
                                    </div>
								</div>
								<div role="tabpanel" class="tab-pane row padding-top--5" id="dates">
                                    <div id="cto34_oficialStartDateContainer" class="form-group col-sm-5">
                                        <label for="cto34_oficialStartDateFormated" class="form-label-full">Inicio real</label>
                                        <div class="input-group input-group-sm date-field">
                                            <input id="cto34_oficialStartDateFormated"
                                                   name="cto34_oficialStartDateFormated"
                                                   type="text"
                                                   value="{{ old('cto34_oficialStartDateFormated') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm date-formated">
                                            <input name="cto34_oficialStartDate" type="hidden" value="{{ old('cto34_oficialStartDate') }}">
                                            <span class="input-group-addon" style="background-color: #fff">
                                                <span class="fa fa-calendar fa-fw text-primary"></span>
                                            </span>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-date-minus">
                                                    <span class="fa fa-minus fa-fw"></span>
                                                </button>
                                                <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                <button type="button" class="btn btn-default btn-date-plus">
                                                    <span class="fa fa-plus fa-fw"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="cto34_oficialEndDateContainer" class="form-group col-sm-5">
                                        <label for="cto34_oficialEndDateFormated" class="form-label-full">Termino real</label>
                                        <div class="input-group input-group-sm date-field">
                                            <input id="cto34_oficialEndDateFormated"
                                                   name="cto34_oficialEndDateFormated"
                                                   type="text"
                                                   value="{{ old('cto34_oficialEndDateFormated') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm date-formated">
                                            <input name="cto34_oficialEndDate" type="hidden" value="{{ old('cto34_oficialEndDate') }}">
                                            <span class="input-group-addon" style="background-color: #fff">
                                                <span class="fa fa-calendar fa-fw text-primary"></span>
                                            </span>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-date-minus">
                                                    <span class="fa fa-minus fa-fw"></span>
                                                </button>
                                                <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                <button type="button" class="btn btn-default btn-date-plus">
                                                    <span class="fa fa-plus fa-fw"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
									<div class="form-group col-sm-2">
										<label class="form-label-full">Duración oficial</label>
										<input id="cto34_oficialDuration"
											   name="cto34_oficialDuration"
											   type="text"
											   value="{{ old('cto34_oficialDuration') }}"
											   class="form-control form-control-plain input-sm" readonly>
									</div>
									<div class="clearfix"></div>
                                    <div id="cto34_realStartDateContainer" class="form-group col-sm-5">
                                        <label for="cto34_realStartDateFormated" class="form-label-full">Inicio real</label>
                                        <div class="input-group input-group-sm date-field">
                                            <input id="cto34_realStartDateFormated"
                                                   name="cto34_realStartDateFormated"
                                                   type="text"
                                                   value="{{ old('cto34_realStartDateFormated') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm date-formated">
                                            <input name="cto34_realStartDate" type="hidden" value="{{ old('cto34_realStartDate') }}">
                                            <span class="input-group-addon" style="background-color: #fff">
                                                <span class="fa fa-calendar fa-fw text-primary"></span>
                                            </span>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-date-minus">
                                                    <span class="fa fa-minus fa-fw"></span>
                                                </button>
                                                <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                <button type="button" class="btn btn-default btn-date-plus">
                                                    <span class="fa fa-plus fa-fw"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="cto34_realEndDateContainer" class="form-group col-sm-5">
                                        <label for="cto34_realEndDateFormated" class="form-label-full">Termino real</label>
                                        <div class="input-group input-group-sm date-field">
                                            <input id="cto34_realEndDateFormated"
                                                   name="cto34_realEndDateFormated"
                                                   type="text"
                                                   value="{{ old('cto34_realEndDateFormated') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm date-formated">
                                            <input name="cto34_realEndDate" type="hidden" value="{{ old('cto34_realEndDate') }}">
                                            <span class="input-group-addon" style="background-color: #fff">
                                                <span class="fa fa-calendar fa-fw text-primary"></span>
                                            </span>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-date-minus">
                                                    <span class="fa fa-minus fa-fw"></span>
                                                </button>
                                                <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                <button type="button" class="btn btn-default btn-date-plus">
                                                    <span class="fa fa-plus fa-fw"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
									<div class="form-group col-sm-2">
										<label class="form-label-full">Duración real</label>
										<input id="cto34_realDuration"
											   name="cto34_realDuration"
											   type="text"
											   value="{{ old('cto34_realDuration') }}"
											   class="form-control form-control-plain input-sm" readonly>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane row padding-top--5" id="details">
									<div class="form-group col-sm-4">
										<label class="form-label-full">Superficie interior</label>
										<input id="cto34_innerSurface"
											   name="cto34_innerSurface"
											   type="text"
											   value="{{ old('cto34_innerSurface') }}"
											   class="form-control form-control-plain input-sm">
									</div>
									<div class="form-group col-sm-4">
										<label class="form-label-full">Superficie exterior</label>
										<input id="cto34_outerSurface"
											   name="cto34_outerSurface"
											   type="text"
											   value="{{ old('cto34_outerSurface') }}"
											   class="form-control form-control-plain input-sm">
									</div>
									<div class="form-group col-sm-4">
										<label class="form-label-full">Superficie total</label>
										<input id="cto34_outerSurfaceTotal"
											   name="cto34_outerSurfaceTotal"
											   type="text"
											   value="{{ old('cto34_outerSurfaceTotal') }}"
											   disabled
											   class="form-control form-control-plain input-sm">
									</div>
									<div class="clearfix"></div>
									<div class="form-group col-sm-4">
										<label class="form-label-full">Tipo de obra</label>
                                        <select name="cto34_type" id="" class="form-control input-sm">
                                            <option value="">Seleccionar opción</option>
                                            <option value="Edificación">Edificación</option>
                                        </select>
									</div>
									<!--<div class="form-group col-sm-4">
										<label class="form-label-full">Genero</label>
										<input id="cto34_kind"
											   name="cto34_kind"
											   type="text"
											   value="{{ old('cto34_kind') }}"
											   class="form-control form-control-plain input-sm">
									</div>-->
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
	@include('panel.constructionwork.home.shared.filter-modal')
@endsection
{{--
    Scripts footer
    Se agregan script js al archivo base de las vistas
--}}
@push('scripts_footer')
	<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/moment.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
	<script src="{{ asset('assets/js/hr.js') }}"></script>
	<script src="{{ asset('assets/js/numeral.min.js') }}"></script>
    <script src="{{ asset('assets/js/customer.js') }}"></script>
	<script>
        (function() {
            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList({ fitRelationHeight: 275 });
            app.filterModal();
            app.tooltip();
            app.highlightSearch();
            app.onPageTab();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.dateTimePickerField('#cto34_oficialStartDateContainer');
            app.dateTimePickerField('#cto34_oficialEndDateContainer');
            app.dateTimePickerField('#cto34_realStartDateContainer');
            app.dateTimePickerField('#cto34_realEndDateContainer');

            var customer = new Customer();

            customer.searchWithAjax('#cto34_owner', {
                    url: '{{ url("ajax/action/search/customers") }}',
                    token: '{{ csrf_token() }}',
                    workId: '',
                    optionClass: 'option-newOwner',
                    optionListClass: 'option-owner',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowBusinessModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        optionSelected(data.element);
                    }
                }
            );


        })();

        function optionSelected(element) {

            var select = $(element);
            var option = select.find('option:selected');
            $(element + 'Name').val(option.text());

            if (option.val() === '') {
                return;
            }

            if (option.val() === '-1') {
                $('#modalDeparture').modal('show');
                return;
            }

            console.log(option.val());

            //$('#cto34_subdeparture_departure').val(select.val());

            var elementId = '#cto34_address';
            $(elementId).prop('disabled', true);

            var request = $.ajax({
                url: '{{ url('/ajax/search/businessAddress') }}',
                type: 'post',
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: {
                    id: option.val()
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                console.log(result);

                if (result.addresses.length !== 0) {

                    var addresses = result.addresses;
                    var options = '';
                    options += '<option value="" >Seleccionar opción</option>';

                    console.log(addresses);

                    $.each(addresses, function(index, value) {
                        options += '<option value="' + value.tbDirEmpresaDomicilioID + '">' +  value.address.DirDomicilioCompleto + '</option>';
                    });

                    $(elementId).html(options);
                }

                $(elementId).prop('disabled', false);

            });

            request.fail(function(xhr, textStatus) {
                alert('Ocurrio un error');
            });
        }
	</script>
@endpush
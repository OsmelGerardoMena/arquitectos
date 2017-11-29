@extends('layouts.base')

{{-- Se registran los archivos css requeridos para esta sección --}}
@push('styles_head')
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
								@include('panel.system.person.shared.content-search')
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
								@foreach ($persons['all'] as $index => $person)
									<a id="item{{ $person->tbDirPersonaID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $person->tbDirPersonaID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
										<h4 class="list-group-item-heading">{{ $person->PersonaAlias }}</h4>
										<p class="text-muted small">
											{{ $person->PersonaNombreCompleto }}
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
									<li role="presentation">
										<a href="#details" aria-controls="details" role="tab" data-toggle="tab">
											Detalles
										</a>
									</li>
									<li role="presentation" class="disabled">
										<a href="#">
											Teléfonos
										</a>
									</li>
									<li role="presentation" class="disabled">
										<a href="#">
											Correos
										</a>
									</li>
									<li role="presentation" class="disabled">
										<a href="#">
											Direcciones
										</a>
									</li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">
                                            Comentarios
                                        </a>
                                    </li>
								</ul>
							</div>
                            <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">
                                <div class="tab-content col-sm-12 margin-bottom--20">
                                    <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label class="form-label-full">Imagen</label>
                                                    <div class="panel-item--image"><p>No disponible.</p></div>
                                                    <input id="cto34_img"
                                                           name="cto34_img"
                                                           type="file"
                                                           value="{{ old('cto34_img') }}"
                                                           class="form-control form-control-plain input-sm"
                                                           accept="image/gif, image/jpeg, image/jpg, image/png" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <!--<div class="form-group col-sm-6">
                                                    <label for="cto34_dependency" class="form-label-full">Dependencia</label>
                                                    <input id="cto34_dependency"
                                                           name="cto34_dependency"
                                                           type="text"
                                                           value="{{ old('cto34_dependency') }}"
                                                           disabled
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <div class="form-group col-sm-12">
                                                    <label class="form-label-full">Nombre por apellidos</label>
                                                    <input type="text"
                                                           value=""
                                                           disabled
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label class="form-label-full">Nombre directo</label>
                                                    <input type="text"
                                                           value=""
                                                           disabled
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label class="form-label-full">Nombre completo</label>
                                                    <input type="text"
                                                           value=""
                                                           disabled
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_gender" class="form-label-full">Género</label>
                                                    <select name="cto34_gender" id="cto34_gender" class="form-control input-sm">
                                                        <option value="">Seleccionar genero</option>
                                                        <option value="Masculino">Masculino</option>
                                                        <option value="Femenino">Femenino</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_personPrefix" class="form-label-full">Prefijo</label>
                                                    <input id="cto34_personPrefix"
                                                           name="cto34_personPrefix"
                                                           type="text"
                                                           value="{{ old('cto34_personPrefix') }}"
                                                           list="personPrefix"
                                                           class="form-control form-control-plain input-sm">
                                                    <datalist id="personPrefix">
                                                        <option value="Sr.">
                                                        <option value="Sra.">
                                                        <option value="Lic.">
                                                        <option value="Arq.">
                                                        <option value="Ing.">
                                                        <option value="Dr.">
                                                    </datalist>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_name" class="form-label-full">Nombre(s)</label>
                                                    <input id="cto34_name"
                                                           name="cto34_name"
                                                           type="text"
                                                           value="{{ old('cto34_name') }}"
                                                           autocomplete="off"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_lastName" class="form-label-full">Apellido Paterno</label>
                                                    <input id="cto34_lastName"
                                                           name="cto34_lastName"
                                                           type="text"
                                                           value="{{ old('cto34_lastName') }}"
                                                           autocomplete="off"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_lastName2" class="form-label-full">Apellido Materno</label>
                                                    <input id="cto34_lastName2"
                                                           name="cto34_lastName2"
                                                           type="text"
                                                           value="{{ old('cto34_lastName2') }}"
                                                           autocomplete="off"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_greeting" class="form-label-full">Saludo</label>
                                                    <input id="cto34_greeting"
                                                           name="cto34_greeting"
                                                           type="text"
                                                           value="{{ old('cto34_greeting') }}"
                                                           autocomplete="off"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_birthdateText" class="form-label-full">Fecha de nacimiento</label>
                                                    <div id="cto34_birthdateContainer" class="input-group input-group-sm date-field">
                                                        <input id="cto34_birthdateText"
                                                               name="cto34_birthdateText"
                                                               type="text"
                                                               readonly="readonly"
                                                               value="{{ old('cto34_birthdateText') }}"
                                                               class="form-control form-control-plain input-sm date-formated">
                                                        <input name="cto34_birthdate" type="hidden"  value="{{ old('cto34_birthdate') }}">
                                                        <span class="input-group-addon" style="background-color: #fff">
                                                            <span class="fa fa-calendar fa-fw text-primary"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_idType" class="form-label-full">Identificación tipo</label>
                                            <input id="cto34_idType"
                                                   name="cto34_idType"
                                                   type="text"
                                                   value="{{ old('cto34_idType') }}"
                                                   list="idType"
                                                   class="form-control form-control-plain input-sm">
                                            <datalist id="idType">
                                                <option value="IFE">
                                                <option value="Pasaporte">
                                                <option value="Licencia">
                                            </datalist>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_idNumber" class="form-label-full">Identificación número</label>
                                            <input id="cto34_idNumber"
                                                   name="cto34_idNumber"
                                                   type="text"
                                                   value="{{ old('cto34_idNumber') }}"
                                                   autocomplete="off"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_contactEmergency" class="form-label-full">Contacto de emergencia</label>
                                            <textarea name="cto34_contactEmergency" maxlength="4000" id="cto34_contactEmergency" cols="30" rows="3" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_createdDate" class="form-label-full">Fecha alta</label>
                                            <div id="cto34_createdDateContainer" class="input-group input-group-sm date-field">
                                                <input id="cto34_createdDateText"
                                                       name="cto34_createdDateText"
                                                       type="text"
                                                       readonly="readonly"
                                                       value="{{ old('cto34_createdDateText') }}"
                                                       class="form-control form-control-plain input-sm date-formated">
                                                <input name="cto34_createdDate" type="hidden"  value="{{ old('cto34_createdDate') }}">
                                                <span class="input-group-addon" style="background-color: #fff">
                                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_downDate" class="form-label-full">Fecha baja</label>
                                            <div id="cto34_downDateContainer" class="input-group input-group-sm date-field">
                                                <input id="cto34_downDateText"
                                                       name="cto34_downDateText"
                                                       type="text"
                                                       readonly="readonly"
                                                       value="{{ old('cto34_downDateText') }}"
                                                       class="form-control form-control-plain input-sm date-formated">
                                                <input name="cto34_downDate" type="hidden"  value="{{ old('cto34_downDate') }}">
                                                <span class="input-group-addon" style="background-color: #fff">
                                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_hashtag">
                            </form>
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
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
	<script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.tooltip();
            app.filterModal();
            app.dateTimePickerField();
            app.onPageTab();

        })();

	</script>
@endpush
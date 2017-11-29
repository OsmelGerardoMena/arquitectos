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
                        @include('shared.nav-actions-update', [ 'model' => [ 'id' => $users['one']->getKey() ]])
					</div>
					<div class="clearfix"></div>
					<div class="panel-body padding-top--clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item padding-clear padding-bottom--5">
								{{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
								@include('panel.system.user.shared.content-search')
							</div>
							<div id="itemsList">
								@foreach ($users['all'] as $index => $user)
									@if ($user->TbCTOUsuarioID == $users['one']->TbCTOUsuarioID)
										<div id="item{{ $user->TbCTOUsuarioID }}" class="list-group-item active">
											<h4 class="list-group-item-heading">{{ $user->CTOUsuarioNombre }}</h4>
											<p class="small">
												{{ $user->role->CTOUsuarioGrupoNombre }} / {{ Carbon\Carbon::parse($user->created_at )->formatLocalized('%d %B %Y') }}
											</p>
										</div>
										@continue
									@endif
										<a id="item{{ $user->TbCTOUsuarioID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $user->TbCTOUsuarioID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
											<h4 class="list-group-item-heading">{{ $user->CTOUsuarioNombre }}</h4>
											<p class="text-muted small">
												{{ $user->role->CTOUsuarioGrupoNombre }}
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
										<div class="form-group col-sm-6">
											<label for="cto34_user" class="form-label-full">Usuario</label>
											<input id="cto34_user"
												   name="cto34_user"
												   type="text"
												   value="{{ ifempty($users['one']->CTOUsuarioNombre) }}"
												   autocomplete="off"
                                                   readonly
												   class="form-control form-control-plain">
                                            <input id="cto34_userOld"
                                                   name="cto34_userOld"
                                                   type="hidden"
                                                   value="{{ ifempty($users['one']->CTOUsuarioNombre) }}"
                                                   class="form-control form-control-plain">
										</div>
										<div class="form-group col-sm-6">
											<label for="cto34_password" class="form-label-full">Contraseña</label>
											<input id="cto34_password"
												   name="cto34_password"
												   type="password"
												   value=""
                                                   placeholder="xxxxxxxxxxxxxxx"
												   autocomplete="off"
												   class="form-control form-control-plain">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-sm-6">
											<label for="cto34_role" class="form-label-full">Grupo</label>
											<select name="cto34_role" id="cto34_role" class="form-control form-control-plain">
                                                <optgroup label="Opción seleccionada">
                                                    <option value="{{ $users['one']->tbCTOUsuarioGrupoID_CTOUsuario }}">{{ $users['one']->role->CTOUsuarioGrupoNombre }}</option>
                                                </optgroup>
												<option value="0">Seleccionar grupo</option>
												@foreach ($roles['all'] as $role)
													<option value="{{ $role->tbCTOUsuarioGrupoID }}">{{ $role->CTOUsuarioGrupoNombre }}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group col-sm-6">
											<label class="form-label-full">Persona</label>
											<select id="cto34_searchPerson"
													name="cto34_searchPerson"
													data-live-search="true"
													data-width="100%"
													data-style="btn-sm btn-default"
													data-modal-title="Responsable en obra"
													class="selectpicker with-ajax">
												@if(!empty(old('cto34_searchPerson')))
													<option value="{{ old('cto34_searchPerson') }}" selected="selected">
														{{ old('cto34_searchPersonName')  }}
													</option>
												@endif
                                                    @if (!empty($users['one']->person))
                                                        <option value="{{ $users['one']->tbDirPersonasID_CTOUsuario }}" selected="selected">
                                                            {{ $users['one']->person->PersonaNombreCompleto }}
                                                        </option>
                                                    @endif
											</select>
                                            @if (!empty($users['one']->person))
                                                <input type="hidden" id="cto34_searchPersonName" name="cto34_searchPersonName" value="{{ $users['one']->person->PersonaNombreCompleto }}">
                                            @else
                                                <input type="hidden" id="cto34_searchPersonName" name="cto34_searchPersonName" value="{{ old('cto34_searchPersonName')  }}">
                                            @endif
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-sm-6">
											<label for="cto34_registeredAt" class="form-label-full">Fecha de registro</label>
											<input id="cto34_registeredAt"
												   name="cto34_registeredAt"
												   type="text"
												   readonly
												   value="{{ Carbon\Carbon::parse($users['one']->created_at)->formatLocalized('%A %d %B %Y') }}"
												   class="form-control form-control-plain input-sm">
										</div>
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="put">
                                        <input type="hidden" name="_query" value="{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}">
                                        <input type="hidden" name="_hasSearch" value="{{ isset($filter['queries']['q']) ? 1 : 0 }}">
                                        <input type="hidden" name="cto34_id" value="{{ $users['one']->getKey() }}">
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

        })();

        function optionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }
	</script>
@endpush
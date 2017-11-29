@extends('layouts.base')
{{-- Se registran los archivos css requeridos para esta sección --}}
@push('styles_head')
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
@endpush
@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
					<div class="panel-body">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item padding-clear padding-bottom--10">
								<form action="{{ $navigation['base'] }}/search" method="get">
									<div class="input-group input-group-sm">
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
                            <div class="list-group-item active">
                                <h4 class="list-group-item-heading">
                                    {{ $emails['one']->CorreoUsuario }} <span class="fa fa-caret-right fa-fw"></span>
                                </h4>
                                <p class="small">
                                    {{ Carbon\Carbon::parse($emails['one']->created_at )->formatLocalized('%d %B %Y') }}
                                </p>
                            </div>
							{{-- 
								Lista de registros

								Muestra los registros como un menú permitiendo
								el acceso más rápido a la información.
								<data-list>
							--}}
							@foreach ($emails['all'] as $index => $b)
                                @if ($b->TbCorreoCtasID == $emails['one']->TbCorreoCtasID)
                                    @continue
                                @endif
								<a href="{{ $navigation['base'] }}/update/{{ $b->TbCorreoCtasID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item {{ ($b->TbCorreoCtasID == $emails['one']->TbCorreoCtasID) ? 'active' : '' }}">
									<h4 class="list-group-item-heading">{{ $b->CorreoUsuario }}</h4>
									<p class="small {{ ($b->TbCorreoCtasID == $emails['one']->TbCorreoCtasID) ? '' : 'text-muted' }}">
										{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
									</p>
								</a>
							@endforeach
							{{-- </data-list> --}}
						</div>
						<div>
							<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
								{{-- Formulario para eliminar un registro --}}
								@include('panel.system.email.shared.delete-form', ['emails' => $emails])
								<div class="col-sm-12">
									<ul class="nav nav-actions navbar-nav navbar-right">
										<li class="save">
											<a href="#" id="updateSubmitButton" class="is-tooltip" data-placement="bottom" title="Guardar">
                                                <span class="fa-stack fa-lg">
                                                    <i class="fa fa-circle fa-stack-2x"></i>
                                                    <i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
                                                </span>
											</a>
										</li>
										<li>
											<a href="{{ $navigation['base'] }}/save" class="is-tooltip confirm-leave" data-placement="bottom" title="Nuevo correo">
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
												<a href="{{ $navigation['base'] }}/info/{{ $emails['one']->TbCorreoCtasID }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
                                                    <span class="fa-stack text-danger fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                                    </span>
												</a>
											@elseif ($navigation['from'] == 'search')
												<a href="{{ $navigation['base'] }}/search/info/{{ $emails['one']->TbCorreoCtasID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
                                                    <span class="fa-stack text-danger fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                                    </span>
												</a>
											@elseif ($navigation['from'] == 'filter')
												<a href="{{ $navigation['base'] }}/filter/info/{{ $emails['one']->TbCorreoCtasID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
                                                    <span class="fa-stack text-danger fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                                    </span>
												</a>
											@endif
										</li>
									</ul>
								</div>
								<form id="updateForm" action="{{ $navigation['base'] }}/action/update" method="POST" accept-charset="utf-8" class="col-sm-12">
								
                      <div class="row">
                          <div class="col-sm-12">
                              @include('layouts.alerts', ['errors' => $errors])
                          </div>
                          <div class="col-sm-12 margin-top--10">
                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_business" class="form-label-full">Empresa</label>
                                                <select name="cto34_business" id="cto34_business" class="form-control input-sm">
                                                    <option value="">Seleccionar opción</option>
                                                    @foreach ($emails['business'] as $user)
                                                        <option {{ $emails['one']->TbMiEmpresaID_CorreoCta == $user->TbmiEmpresaID ?'selected':'' }} value="{{ $user->TbmiEmpresaID }}">{{ $user->miEmpresaAlias }}</option>
                                                    @endforeach  
                                                </select>
                                            </div>     
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_asigned" class="form-label-full">Prestador asignado</label>
                                                <select id="cto34_asigned"
                                                        name="cto34_asigned"
                                                        data-live-search="true"
                                                        data-width="100%"
                                                        data-style="btn-sm btn-default"
                                                        data-modal-title="Cliente directo"
                                                        class="selectpicker with-ajax">

                                                        <option value="{{ $emails['one']->person->tbDirPersonasID }}" selected="selected">
                                                            {{ $emails['one']->person->PersonaAlias  }}
                                                        </option>
                                                </select>
                                                <input type="hidden" id="cto34_asignedName" name="cto34_asignedName" value="{{ $emails['one']->person->PersonaAlias }}">
                                            </div>

                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_domain" class="form-label-full">Dominio</label>
                                                <select name="cto34_domain" id="cto34_domain" class="form-control input-sm">
                                                    <option value="">Seleccionar opción</option>
                                                    <option {{ $emails['one']->CorreoDominio == 'bfcarquitectos.com'?'selected':'' }} value="bfcarquitectos.com" >bfcarquitectos.com</option>
                                                    <option {{ $emails['one']->CorreoDominio =='gmail.com'?'selected':'' }}  value="gmail.com">gmail.com</option>
                                                    <option {{ $emails['one']->CorreoDominio =='outlook.com'?'selected':'' }}  value="outlook.com">outlook.com</option>
                                                    <option {{ $emails['one']->CorreoDominio =='yahoo.com.mx'?'selected':'' }} value="yahoo.com.mx">yahoo.com.mx</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_user" class="form-label-full">Usuario</label>
                                                <input id="cto34_user"
                                                       name="cto34_user"
                                                       type="text"
                                                       value="{{ $emails['one']->CorreoUsuario }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_email" class="form-label-full">Correo electrónico</label>
                                                <input id="cto34_email"
                                                       name="cto34_email"
                                                       type="text"
                                                       value="{{ $emails['one']->CorreoElectronico }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_date_begin" class="form-label-full">Fecha de apertura</label>
                                                <input id="cto34_date_begin"
                                                       name="cto34_date_begin"
                                                       type="date"
                                                       value="{{ Carbon\Carbon::parse($emails['one']->CorreoFechaDeApertura )->format('Y-m-d') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_date_down" class="form-label-full">Fecha de baja</label>
                                                <input id="cto34_date_down"
                                                       name="cto34_date_down"
                                                       type="date"
                                                       value="{{ Carbon\Carbon::parse($emails['one']->CorreoBajaFecha )->format('Y-m-d') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_reason_down" class="form-label-full">Razones de baja</label>
                                                <input id="cto34_reason_down"
                                                       name="cto34_reason_down"
                                                       type="text"
                                                       value="{{ $emails['one']->CorreoBajaRazones }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6 col-md-4">
                                                <label for="cto34_coust" class="form-label-full">Costo del servicio</label>
                                                <select name="cto34_coust" id="cto34_coust" class="form-control input-sm">
                                                    <option value="">Seleccionar opción</option>
                                                    <option {{ $emails['one']->CorreoCosto == 'Cuota anual'?'selected':'' }} value="Cuota anual">Cuota anual</option>
                                                    <option {{ $emails['one']->CorreoCosto == 'Cuota mensual'?'selected':'' }} value="Cuota mensual">Cuota mensual</option>
                                                    <option {{ $emails['one']->CorreoCosto == 'Incluido en dominio'?'selected':'' }} value="Incluido en dominio">Incluido en dominio</option>
                                                    <option {{ $emails['one']->CorreoCosto == 'Gratuito'?'selected':'' }} value="Gratuito">Gratuito</option>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6 col-md-12">
                                                <label for="cto34_comment" class="form-label-full">Comentarios</label>
                                                <textarea id="cto34_comment"
                                                       name="cto34_comment" maxlength="4000"
                                                       value="{{ $emails['one']->CorreoComentarios }}"
                                                       class="form-control form-control-plain input-sm"></textarea>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6 col-md-2 text-left">
                                                <label for="cto34_inactive" class="form-label-full">Inactivo</label>
                                                <input type="checkbox" class="form-control form-control-plain input-sm"  {{ $emails['one']->RegistroInactivo == 1?'checked':'' }} name="cto34_inactive">
                                            </div>
                                            <div class="form-group col-sm-6 col-md-2 text-left">
                                                <label for="cto34_close" class="form-label-full">Cerrado</label>
                                                <input type="checkbox" class="form-control form-control-plain input-sm" {{ $emails['one']->RegistroCerrado == 1?'checked disabled':'' }} name="cto34_close"> 
                                            </div>
                          </div>
                      </div>

                                    <input type="hidden" name="cto34_id" value="{{ $emails['one']->TbCorreoCtasID }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
	@include('panel.system.email.shared.filter-modal', ['navigation' => $navigation])
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/person.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
    		app.animateSubmit("updateForm", "updateSubmitButton");
    		app.tooltip();
    		//app.scrollNavActions();
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

    	})();
    </script>
@endpush
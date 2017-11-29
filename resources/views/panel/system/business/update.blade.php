@extends('layouts.base')

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
							{{-- 
								Lista de registros

								Muestra los registros como un menú permitiendo
								el acceso más rápido a la información.
								<data-list>
							--}}
							@foreach ($business['all'] as $index => $b)
                                @if ($b->tbDirEmpresaID  == $business['one']->tbDirEmpresaID)
                                    <a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item active">
                                        <h4 class="list-group-item-heading">
                                            {{ $b->EmpresaAlias }} <span class="fa fa-caret-right fa-fw"></span>
                                        </h4>
                                        <p class="small">
                                            {{ $b->EmpresaNombreComercial }}
                                        </p>
                                    </a>
                                    @continue
                                @endif
								<a href="{{ $navigation['base'] }}/update/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item {{ ($b->tbDirEmpresaID == $business['one']->tbDirEmpresaID) ? 'active' : '' }}">
                                    <h4 class="list-group-item-heading">
                                        {{ $b->EmpresaAlias }} <span class="fa fa-caret-right fa-fw"></span>
                                    </h4>
                                    <p class="small">
                                        {{ $b->EmpresaNombreComercial }}
                                    </p>
								</a>
							@endforeach
							{{-- </data-list> --}}
						</div>
						<div>
							<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
								{{-- Formulario para eliminar un registro --}}
								@include('panel.system.business.shared.delete-form', ['business' => $business])
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
												<a href="{{ $navigation['base'] }}/info/{{ $business['one']->tbDirEmpresaID }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
                                                    <span class="fa-stack text-danger fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                                    </span>
												</a>
											@elseif ($navigation['from'] == 'search')
												<a href="{{ $navigation['base'] }}/search/info/{{ $business['one']->tbDirEmpresaID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
                                                    <span class="fa-stack text-danger fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                                    </span>
												</a>
											@elseif ($navigation['from'] == 'filter')
												<a href="{{ $navigation['base'] }}/filter/info/{{ $business['one']->tbDirEmpresaID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
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
									<div class="table-responsive">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                @include('layouts.alerts', ['errors' => $errors])
                                            </div>
                                            <div class="col-sm-12 margin-top--10">
                                                <div class="form-group col-sm-4">
                                                    <img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto">
                                                    <input type="file" name="cto34_imgLogo" id="" class="form-control input-sm">
                                                    <p class="help-block small">Subir logo (Max. 10 MB, formato: JPG o PNG)</p>
                                                </div>
                                                <div class="form-group col-sm-8">
                                                    <label for="cto34_alias" class="form-label-full">Empresa alias</label>
                                                    <input id="cto34_alias"
                                                           name="cto34_alias"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaAlias }}"
                                                           class="form-control form-control-plain input-sm">
                                                    <label for="cto34_legalName" class="form-label-full margin-top--10">Razón social</label>
                                                    <input id="cto34_legalName"
                                                           name="cto34_legalName"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaRazonSocial }}"
                                                           class="form-control form-control-plain input-sm">
                                                    <label for="cto34_commercialName" class="form-label-full margin-top--10">Nombre comercial</label>
                                                    <input id="cto34_commercialName"
                                                           name="cto34_commercialName"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaNombreComercial }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_dependency" class="form-label-full">Dependencia</label>
                                                    <input id="cto34_dependency"
                                                           name="cto34_dependency"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaDependencia }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_especiality" class="form-label-full">Especialidad</label>
                                                    <input id="cto34_especiality"
                                                           name="cto34_especiality"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaEspecialidad }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_type" class="form-label-full">Tipo de persona</label>
                                                    <select name="cto34_type" id="cto34_type" class="form-control input-sm">
                                                        <optgroup label="Opción seleccionada">
                                                            <option value="{{ $business['one']->EmpresaTipoPersona }}">{{ $business['one']->EmpresaTipoPersona }}</option>
                                                        </optgroup>
                                                        <optgroup label="Opciones">
                                                            <option value="Física">Física</option>
                                                            <option value="Moral">Moral</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_slogan" class="form-label-full">Slogan</label>
                                                    <input id="cto34_slogan"
                                                           name="cto34_slogan"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaSlogan }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_website" class="form-label-full">Página web</label>
                                                    <input id="cto34_website"
                                                           name="cto34_website"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaPaginaWeb }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_legalId" class="form-label-full">RFC</label>
                                                    <input id="cto34_legalId"
                                                           maxlength="25"
                                                           name="cto34_legalId"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaRFC }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_imssNum" class="form-label-full">Número de IMSS</label>
                                                    <input id="cto34_imssNum"
                                                           name="cto34_imssNum"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaIMSSNumero }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_infonavitNum" class="form-label-full">Número de INFONAVIT</label>
                                                    <input id="cto34_infonavitNum"
                                                           name="cto34_infonavitNum"
                                                           type="text"
                                                           value="{{ $business['one']->EmpresaINFONAVITNumero }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_sector" class="form-label-full">Sector</label>
                                                    <select name="cto34_sector" id="cto34_sector"
                                                            class="form-control input-sm">
                                                        <optgroup label="Opción seleccionada">
                                                            <option value="{{ $business['one']->EmpresaSector }}">{{ $business['one']->EmpresaSector }}</option>
                                                        </optgroup>
                                                        <optgroup label="Opciones">
                                                            <option value="Privado">Privado</option>
                                                            <option value="Público">Público</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_comments" class="form-label-full">Comentarios</label>
                                                    <textarea name="cto34_comments" maxlength="4000" id="cto34_comments" cols="30" rows="3" class="form-control">{{ $business['one']->EmpresaComentarios }}</textarea>
                                                </div>
                                            </div>
                                        </div>
									</div>
                                    <input type="hidden" name="cto34_id" value="{{ $business['one']->tbDirEmpresaID }}">
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
	@include('panel.system.business.shared.filter-modal', ['navigation' => $navigation])
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
    		app.tooltip();
    		//app.scrollNavActions();


    	})();
    </script>
@endpush
@extends('layouts.base')

{{-- Se registran los archivos css requeridos para esta sección --}}
@push('styles_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
@endpush

@section('content')
	{{--
		
		Contenido de la sección
		
		Se mostra toda la lista de registro y la información de un registro
		seleccionado.

		<data-content>
	--}}
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
					<div class="panel-body">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item padding-clear padding-bottom--10">
								<form action="{{ $navigation['base'] }}/search" method="get">
									<div class="input-group input-group-sm">
										<input name="q" type="text" class="form-control form-control-plain" placeholder="Busqueda">
										<span class="input-group-btn">
									    	<button class="btn btn-default" type="submit">
									    		<span class="fa fa-search fa-fw"></span>
									    	</button>
									    </span>
									</div>
								</form>
							</div>
                            <div class="list-group-item active">
                                <h4 class="list-group-item-heading">
                                    Nuevo <span class="fa fa-caret-right fa-fw"></span>
                                </h4>
                                <p class="small">
                                    {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}
                                </p>
                            </div>
                            @foreach ($business['all'] as $index => $b)
                                <a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
                                    <h4 class="list-group-item-heading">{{ $b->EmpresaAlias }}</h4>
                                    <p class="text-muted small">
                                        {{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
                                    </p>
                                </a>
                            @endforeach
						</div>
                        <div class="col-sm-7 col-md-8 col-lg-9 panel-item">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="nav nav-actions navbar-nav navbar-right">
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
                                <form id="saveForm" action="{{ $navigation['base'] }}/action/save" method="POST" accept-charset="utf-8" class="col-sm-12  margin-top--10">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            @include('layouts.alerts', ['errors' => $errors])
                                        </div>
                                        <div class="col-sm-12">
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
                                                       value="{{ old('cto34_alias') }}"
                                                       class="form-control form-control-plain input-sm">
                                                <label for="cto34_legalName" class="form-label-full margin-top--10">Razón social</label>
                                                <input id="cto34_legalName"
                                                       name="cto34_legalName"
                                                       type="text"
                                                       value="{{ old('cto34_legalName') }}"
                                                       class="form-control form-control-plain input-sm">
                                                <label for="cto34_commercialName" class="form-label-full margin-top--10">Nombre comercial</label>
                                                <input id="cto34_commercialName"
                                                       name="cto34_commercialName"
                                                       type="text"
                                                       value="{{ old('cto34_commercialName') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_dependency" class="form-label-full">Dependencia</label>
                                                <input id="cto34_dependency"
                                                       name="cto34_dependency"
                                                       type="text"
                                                       value="{{ old('cto34_dependency') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_especiality" class="form-label-full">Especialidad</label>
                                                <input id="cto34_especiality"
                                                       name="cto34_especiality"
                                                       type="text"
                                                       value="{{ old('cto34_especiality') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_type" class="form-label-full">Tipo de persona</label>
                                                <select name="cto34_type" id="cto34_type" class="form-control input-sm">
                                                    <option value="">Seleccionar opción</option>
                                                    <option value="Física">Física</option>
                                                    <option value="Moral">Moral</option>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_slogan" class="form-label-full">Slogan</label>
                                                <input id="cto34_slogan"
                                                       name="cto34_slogan"
                                                       type="text"
                                                       value="{{ old('cto34_slogan') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_website" class="form-label-full">Página web</label>
                                                <input id="cto34_website"
                                                       name="cto34_website"
                                                       type="text"
                                                       value="{{ old('cto34_website') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_legalId" class="form-label-full">RFC</label>
                                                <input id="cto34_legalId"
                                                       maxlength="25"
                                                       name="cto34_legalId"
                                                       type="text"
                                                       value="{{ old('cto34_legalId') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_imssNum" class="form-label-full">Número de IMSS</label>
                                                <input id="cto34_imssNum"
                                                       name="cto34_imssNum"
                                                       type="text"
                                                       value="{{ old('cto34_imssNum') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_infonavitNum" class="form-label-full">Número de INFONAVIT</label>
                                                <input id="cto34_infonavitNum"
                                                       name="cto34_infonavitNum"
                                                       type="text"
                                                       value="{{ old('cto34_infonavitNum') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="cto34_sector" class="form-label-full">Sector</label>
                                                <select name="cto34_sector" id="cto34_sector"
                                                        class="form-control input-sm">
                                                    <option value="">Seleccionar opción</option>
                                                    <option value="Privado">Privado</option>
                                                    <option value="Público">Público</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label for="cto34_comments" class="form-label-full">Comentarios</label>
                                                <textarea name="cto34_comments" maxlength="4000" id="cto34_comments" cols="30" rows="3" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            </div>
                        </div>
					</div>
				</div>
                {{-- Se mostrara el menú de las página disponibles --}}
                {{ $navigation['pagination'] }}
			</div>
		</div>
	</div>
	{{-- </data-content> --}}
	{{-- Modal para filtrar --}}
	@include('panel.system.business.shared.filter-modal', ['navigation' => $navigation])
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
	<script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.animateSubmit("saveForm", "addSubmitButton");
    		app.scrollNavActions();

	    	$('.panel-item').matchHeight({byRow: true});
	    	$(document).tooltip({selector: '.is-tooltip'});

    	})();
    </script>
@endpush
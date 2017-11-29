@extends('layouts.base')

@section('content')
	{{--
		Alertas 
		Se mostraran las alertas que el sistema envíe
		si se redirecciona a index

		<alerts>
	--}}
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				@include('layouts.alerts', ['errors' => $errors])
			</div>
		</div>
	</div>
	{{-- </alerts> --}}
	{{--
		
		Contenido de la sección
		
		Se mostra toda la lista de registro y la información de un registro
		seleccionado.

		<data-content>
	--}}
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section">
					@if (count($business['all']) == 0)
						<div class="panel-body text-center">
							<h3>No hay empresas registradas</h3>
							<a href="{{ $navigation['base'] }}/save" class="btn btn-link btn-lg">Agregar empresa</a>
						</div>
					@else
						<div class="panel-body">
							<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
								<div class="list-group-item padding-clear padding-bottom--10">
									<form action="{{ $navigation['base'] }}/search" method="get">
										<div class="input-group input-group-sm">
											<input id="search" name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de empresa" value="{{ $search['query'] }}">
                                            @if (count($filter['queries']) > 1)
                                                <input type="hidden" name="status" value="{{ ifempty($filter['queries']['status'], '') }}">
                                                <input type="hidden" name="type" value="{{ ifempty($filter['queries']['type'], '') }}">
                                                <input type="hidden" name="sector" value="{{ ifempty($filter['queries']['sector'], '') }}">
                                            @endif
                                            <span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
                                                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalFilter">
										    		<span class="fa fa-filter fa-fw"></span>
										    	</button>
										    	<a href="{{ $navigation['base'] }}" class="btn btn-link btn-sm">
													<div class="text-danger"><span class="fa fa-times fa-fw"></span></div>
												</a>
										    </span>
										</div>
									</form>
								</div>
								@foreach ($business['all'] as $index => $b)
									@if ($b->tbDirEmpresaID == $business['one']->tbDirEmpresaID)
										<div class="list-group-item active">
                                            <h4 class="list-group-item-heading">
                                                {{ $b->EmpresaRazonSocial }}
                                            </h4>
                                            <p class="small">
                                                {{ $b->EmpresaNombreComercial }}
                                            </p>
										</div>
										@continue
									@endif
									<a href="{{ $navigation['base'] }}/search/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?'.$filter['query'] : '?'.$filter['query'] }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">
                                            {{ $b->EmpresaRazonSocial }}
                                        </h4>
                                        <p class="small">
                                            {{ $b->EmpresaNombreComercial }}
                                        </p>
									</a>
								@endforeach
							</div>
							<div>
								<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
									{{-- 

										Formulario para eliminar un registro 
										Si es index hay que sobre escribre la variable $model['one']
									--}}
									@include('panel.system.business.shared.delete-form')
                                    <div class="col-sm-12">
                                        <ul class="nav nav-actions navbar-nav navbar-right">
                                            <li>
                                                <a href="{{ $navigation['base'] }}/save" class="is-tooltip" data-placement="bottom" title="Nueva empresa">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
									</span>
                                                </a>
                                            </li>
                                            <!--<li>
                                                <a href="#" data-placement="bottom" title="Filtrar"
                                                   data-toggle="modal" data-target="#modalFilter"
                                                   class="is-tooltip">
											<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-filter fa-stack-1x fa-inverse"></i>
									</span>
                                                </a>
                                            </li>-->
                                            <li>
                                                <a href="{{ $navigation['base'] }}/update/{{ $business['one']->tbDirEmpresaID }}{{ ($navigation['page'] > 0) ? '?'.$filter['query'] : '?'.$filter['query'] }}" class="is-tooltip" data-placement="bottom" title="Editar">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
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
                                        </ul>
                                    </div>
									<div class="col-sm-12">
                                        <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a>
                                            </li>
                                            <!--<li role="presentation">
                                                            <a href="#concepts" aria-controls="profile" role="tab" data-toggle="tab">Conceptos</a>
                                                        </li>-->
                                            <li role="presentation">
                                                <a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab">Direcciones</a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comentarios</a>
                                            </li>
                                        </ul>
                                        <form action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8" class="cto_form row margin-top--10 padding-bottom--30">
                                            <div class="tab-content col-sm-12">
                                                <div role="tabpanel" class="tab-pane active" id="general">
                                                    <div class="row">
                                                        <div class="form-group col-sm-4">
                                                            <img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto">
                                                        </div>
                                                        <div class="form-group col-sm-8">
                                                            <label>Alias</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaAlias) }}</p>
                                                            <label>Razón Social</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaRazonSocial) }}</p>
                                                            <label>Nombre Comercial</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaNombreComercial) }}</p>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">Dependencia</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaDependencia) }}</p>
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">Especialidad</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaEspecialidad) }}</p>
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">Tipo de persona</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaTipoPersona) }}</p>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">Slogan</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaSlogan) }}</p>
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">Página web</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaPaginaWeb) }}</p>
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">RFC</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaRFC) }}</p>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">Número de IMSS</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaIMSSNumero) }}</p>
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">Número de INFONAVIT</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaINFONAVITNumero) }}</p>
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label class="form-label-full">Sector</label>
                                                            <p class="help-block">{{ ifempty($business['one']->EmpresaSector) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="addresses">
                                                    @forelse($business['one']->addresses as $businessAddress)
                                                        <div class="col-sm-12">
                                                            <b>Domicilio completo</b><br>
                                                            {{ ifempty($businessAddress->address->DirDomicilioCompleto) }}
                                                            <hr>
                                                        </div>
                                                    @empty
                                                        <div class="col-sm-12 text-center">
                                                            <h3>No hay direcciones registradas</h3>
                                                        </div>
                                                    @endforelse
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="comments">
                                                    @forelse($business['one']->comments as $comment)
                                                        <div class="col-sm-12">
                                                            <b>Comentario por {{ $comment->user->person->PersonaNombreCompleto  }}</b><br>
                                                            <p>
                                                                {{ $comment->Comentario }}<br>
                                                                <small class="text-muted">
                                                                    {{  Carbon\Carbon::parse($comment->ComentarioFecha)->formatLocalized('%A %d %B %Y') }}
                                                                </small>
                                                            </p>
                                                            <hr>
                                                        </div>
                                                    @empty
                                                        <div class="col-sm-12 text-center">
                                                            <h3>No hay comentarios registrados</h3>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </form>
									</div>
								</div>
							</div>
						</div>
					@endif
				</div>
				{{-- Se mostrara el menú de las página disponibles --}}
				{{ $navigation['pagination'] }}
			</div>
		</div>
	</div>
	{{-- </data-content> --}}
	{{-- Modal para filtrar --}}
	@include('panel.system.business.shared.filter-modal')
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script src="{{ asset('assets/js/hr.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
            app.tooltip();

            var search = $('#search');

            if (search.val().length !== 0) {

                new HR(".help-block", {
                    highlight: search.val(),
                    backgroundColor: '#e1f5fe'
                }).hr();
            }

            $('#modalFilter').on('show.bs.modal', function() {

                var search = $('#search');
                var searchFilter = $('#searchFilter');
                var filterSearchInput = $('#searchInputFilter');

                if (search.val().length === 0) {
                    searchFilter.removeClass('visible').addClass('hidden');
                } else {
                    searchFilter.removeClass('hidden').addClass('visible');
                    filterSearchInput.val(search.val());
                }

            });
    	})();
    </script>
@endpush
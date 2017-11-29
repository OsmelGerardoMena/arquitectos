@extends('layouts.base')

@section('content')
	{{--
		Alertas 
		Se mostraran las alertas que el sistema envíe
		si se redirecciona a index

		<alerts>
	--}}
	<!--<div class="container">
		<div class="row">
			<div class="col-sm-12">
				@include('layouts.alerts', ['errors' => $errors])
			</div>
		</div>
	</div>-->
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
					@if (count($phones['all']) == 0)
						<div class="panel-body text-center">
							<h3>No hay teléfonos registrados</h3>
							<a href="{{ $navigation['base'] }}/save" class="btn btn-link btn-lg">Agregar teléfono</a>
						</div>
					@else
						<div class="panel-body">
							<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
								<div class="list-group-item padding-clear padding-bottom--10">
									<form action="{{ $navigation['base'] }}/search" method="get">
										<div class="input-group input-group-sm">
											<input name="q" type="text" class="form-control form-control-plain" placeholder="Busqueda" value="{{ $search['query'] }}">
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
								@foreach ($phones['all'] as $index => $b)
									@if ($index == 0)
										<div class="list-group-item active">
											<h4 class="list-group-item-heading">
                                                {{ $b->DirTelefonoNumero }} <span class="fa fa-caret-right fa-fw"></span>
                                            </h4>
											<p class="small">
												{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
											</p>
										</div>
										@continue
									@endif
									<a href="{{ $navigation['base'] }}/filter/info/{{ $b->tbDirTelefonoID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'].'&by='.$filter['query'] : '?by='.$filter['query'] }}" class="list-group-item">
										<h4 class="list-group-item-heading">{{ $b->DirTelefonoNumero }}</h4>
										<p class="text-muted small">
											{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
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
									@include('panel.system.phone.shared.delete-form', ['phones' => [ 'one' => $phones['all'][0]]])
									<div class="col-sm-12">
										<ul class="nav nav-actions navbar-nav navbar-right">
											<li>
												<a href="{{ $navigation['base'] }}/save" class="is-tooltip" data-placement="bottom" title="Nueva teléfono">
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
											<!--<li>
												<a href="{{ $navigation['base'] }}/update/{{ $phones['all'][0]->tbDirEmpresaID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'].'&by='.$filter['query'] : '?by='.$filter['query'] }}" class="is-tooltip" data-placement="bottom" title="Editar">
													<span class="fa-stack fa-lg">
														<i class="fa fa-circle fa-stack-2x"></i>
														<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
													</span>
												</a>
											</li>-->
                                            <li>
                                                <a href="#" class="is-tooltip" data-placement="bottom" title="Restaurar">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-refresh fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                            </li>
											<li>
												<a href="#" id="confirmDeleteButton" class="is-tooltip" data-placement="bottom" title="Eliminar permanentemente">
													<span class="fa-stack text-danger fa-lg">
														<i class="fa fa-circle fa-stack-2x"></i>
														<span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
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
									<div class="col-sm-12">
                                           <div class="form-group col-sm-6 col-md-10">
                                                <label for="cto34_business" class="form-label-full">Empresa</label>
                                                <p class="help-block">{{ $phones['all'][0]->business->EmpresaAlias }}</p>
                                            </div> 
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-10">
                                                <label for="cto34_asigned" class="form-label-full">Persona</label>
                                                <p class="help-block">{{ $phones['all'][0]->person->PersonaAlias }}</p>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-10">
                                                <label for="cto34_label" class="form-label-full">Etiqueta</label>
                                                <p class="help-block">{{ $phones['all'][0]->DirTelefonoEtiqueta }}</p>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_phone_type" class="form-label-full">Teléfono tipo</label>
                                                <p class="help-block">{{ $phones['all'][0]->DirTelefonoTipo }}</p>
                                            </div>

                                            
                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_area_code" class="form-label-full">Clave lada</label>
                                                <p class="help-block">{{ $phones['all'][0]->DirTelefonoLada }}</p>
                                            </div>
                                            <div class="clearfix"></div>


                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_phone_number" class="form-label-full">Número teléfonico</label>
                                                <p class="help-block">{{ $phones['all'][0]->DirTelefonoNumero }}</p>
                                            </div>
                                            <div class="form-group col-sm-6 col-md-6">
                                                <label for="cto34_ext" class="form-label-full">Extensión</label>
                                                <p class="help-block">{{ $phones['all'][0]->DirTelefonoExtension }}</p>
                                            </div>
                                            <div class="clearfix"></div>

                                            <!--<div class="form-group col-sm-6 col-md-10">
                                                <label for="cto34_phone_complete" class="form-label-full">Teléfono completo</label>
                                                <input id="cto34_phone_complete"
                                                       name="cto34_phone_complete"
                                                       type="text"
                                                       value="{{ old('cto34_phone_complete') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div> -->
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-12">
                                                <label for="cto34_comment" class="form-label-full">Comentarios</label>
                                                <p class="help-block">{{ $phones['all'][0]->DirTelefonoComentarios }}</p>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group col-sm-6 col-md-2 text-left">
                                                <label for="cto34_inactive" class="form-label-full">Inactivo</label> <p class="help-block">{{ $phones['all'][0]->RegistroInactivo == 0?'Activo':'Inactivo' }}</p>
                                            </div>
                                            <div class="form-group col-sm-6 col-md-2 text-left">
                                                <label for="cto34_close" class="form-label-full">Cerrado</label>
                                                <p class="help-block">{{ $phones['all'][0]->RegistroCerrado == 0?'Abierto':'Cerrado' }}</p> 
                                            </div>
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
	@include('panel.system.phone.shared.filter-modal')
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
    		app.tooltip();
    		//app.scrollNavActions();
    	})();
    </script>
@endpush
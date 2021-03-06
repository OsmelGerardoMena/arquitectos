@extends('layouts.base')
@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				@include('layouts.alerts', ['errors' => $errors])
			</div>
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
                                        {{ $phones['one']->DirTelefonoNumero }} <span class="fa fa-caret-right fa-fw"></span>
                                    </h4>
									<p class="small">
										{{ Carbon\Carbon::parse($phones['one']->created_at )->formatLocalized('%d %B %Y') }}
									</p>
								</div>
								@foreach ($phones['all'] as $index => $b)
									@if ($b->tbDirTelefonoID  == $phones['one']->tbDirTelefonoID)
										@continue
									@endif
									<a href="{{ $navigation['base'] }}/info/{{ $b->tbDirTelefonoID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
										<h4 class="list-group-item-heading">{{ $b->DirTelefonoNumero }}</h4>
										<p class="small">
											{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
										</p>
									</a>
								@endforeach
							</div>
							<div>
								<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
									{{-- Formulario para eliminar un registro --}}
									@include('panel.system.phone.shared.delete-form')
                                    <div class="col-sm-12">
                                        <ul class="nav nav-actions navbar-nav navbar-right">
                                            <li>
                                                <a href="{{ $navigation['base'] }}/save" class="is-tooltip" data-placement="bottom" title="Nuevo teléfono">
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
                                                <a href="{{ $navigation['base'] }}/update/{{ $phones['one']->tbDirTelefonoID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="is-tooltip" data-placement="bottom" title="Editar">
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
                                            <div class="form-group col-sm-6 col-md-10">
												<label>Empresa</label>
												<p class="help-block">{{ $phones['one']->business->EmpresaAlias }}</p>
                                            </div>     
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6 col-md-10">
												<label>Persona</label>
												<p class="help-block">{{ $phones['one']->person->PersonaNombreCompleto }}</p>
                                            </div> 
                                            <div class="form-group col-sm-6 col-md-10">
												<label>Etiqueta</label>
												<p class="help-block">{{ $phones['one']->DirTelefonoEtiqueta }}</p>
                                            </div>
                                            <div class="form-group col-sm-6 col-md-6">
												<label>Teléfono tipo</label>
												<p class="help-block">{{ $phones['one']->DirTelefonoTipo }}</p>
                                            </div> 
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6 col-md-6">
												<label>Clave lada</label>
												<p class="help-block">{{ $phones['one']->DirTelefonoLada }}</p>
                                            </div> 
                                            <div class="form-group col-sm-6 col-md-6">
												<label>Número teléfonico</label>
												<p class="help-block">{{ $phones['one']->DirTelefonoNumero }}</p>
                                            </div>
                                            <div class="form-group col-sm-6 col-md-6">
												<label>Extensión</label>
												<p class="help-block">{{ $phones['one']->DirTelefonoExtension }}</p>
                                            </div> 
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6 col-md-12">
												<label>Comentarios</label>
												<p class="help-block">{{ $phones['one']->DirTelefonoComentarios }}</p>
                                            </div> 
                                            <div class="form-group col-sm-6 col-md-4">
												<label>Inactivo</label>
												<p class="help-block">{{ $phones['one']->RegistroInactivo == 0?'No':'Si' }}</p>
                                            </div>
                                            <div class="form-group col-sm-6 col-md-4">
												<label>Cerrado</label>
												<p class="help-block">{{ $phones['one']->RegistroCerrado == 0?'No':'Si' }}</p>
                                            </div>
                                            <div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
				</div>
				{{ $navigation['pagination'] }}
			</div>
		</div>
	</div>
	{{-- Modal para filtrar --}}
	@include('panel.system.phone.shared.filter-modal')
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
    		//app.scrollNavActions();
            app.tooltip();
    	})();
    </script>
@endpush
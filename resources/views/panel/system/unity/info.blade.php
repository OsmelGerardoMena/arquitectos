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
	{{--
		Menú de acciones 
		
		Se muestra el título de la sección actual
		y los botones acción

		<navbar-actions>
	--}}
	<div class="navbar-actions">
		<div class="container-fluid">
			<div class="navbar navbar-static-top navbar-inverse margin-bottom--clear">
				<div class="container-fluid">
					<div class="navbar-header">
						<p class="navbar-text">
							{{ $page['title'] }} / {{ $unities['one']->UnidadNombre }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
                        <ul class="nav nav-actions navbar-nav navbar-right">
                            <li>
                                <a href="{{ $navigation['base'] }}/save" class="is-tooltip" data-placement="bottom" title="Nuevo">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
									</span>
                                </a>
                            </li>
                            <li>
                                <a class="disabled">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            @if($unities['all']->count() > 0)
                                <li>
                                    <a href="{{ $navigation['base'] }}/update/{{ $unities['one']->tbUnidadID }}" class="is-tooltip" data-placement="bottom" title="Editar">
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
                            @endif
                            <li>
                                <a class="disabled">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- </navbar-actions> --}}
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
						<div class="panel-body padding-clear">
							<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
								<div class="list-group-item">
									<form action="{{ $navigation['base'] }}/search" method="get">
										<div class="input-group">
											<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de la unidad">
											<span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
                                               <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalFilter">
                                                    <span class="fa fa-filter fa-fw"></span>
                                                </button>
										    </span>
										</div>
									</form>
								</div>
								@foreach ($unities['all'] as $index => $b)
									<a href="{{ $navigation['base'] }}/info/{{ $b->tbUnidadID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item {{ ($b->tbUnidadID == $unities['one']->tbUnidadID) ? 'active' : '' }}">
										<h4 class="list-group-item-heading">
										{{ $b->UnidadNombre }} /
										{{ $b->UnidadTipo }}
										</h4>
										<p class="small {{ ($b->tbUnidadID == $unities['one']->tbUnidadID) ? '' : 'text-muted' }}">
											{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
										</p>
									</a>
								@endforeach
                            <!-- Paginator -->
                            <div class="list-group-item padding-clear padding-top--10">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <a href="{{ $navigation['base'] }}" class="btn btn-default btn-xs">Primero</a>
                                        <a href="{{ $navigation['pagination']['prev']  }}" class="btn btn-default btn-xs">
                                            <span class="fa fa-caret-left fa-fw"></span>
                                        </a>
                                        {{ $navigation['pagination']['current']  }} / {{ $navigation['pagination']['last']  }}
                                        <a href="{{ $navigation['pagination']['next']  }}" class="btn btn-default btn-xs">
                                            <span class="fa fa-caret-right fa-fw"></span>
                                        </a>
                                        <a href="{{ $navigation['base'] }}?page={{ $navigation['pagination']['last']  }}" class="btn btn-default btn-xs">Ultimo</a>
                                    </div>
                                </div>
                            </div>
							</div>
							<div>
								<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
									{{-- Formulario para eliminar un registro --}}
									@include('panel.system.unity.shared.delete-form')
									<h4 class="text-muted panel-section-title">Unidad</h4>
									<div class="table-responsive">
										<table class="table table-info">
											<tbody>
												<tr>
													<td>
														<label>Unidad</label>
														<p class="help-block">{{ $unities['one']->UnidadAlias }}</p>
													</td>
													<td>
														<label>Tipo</label>
														<p class="help-block">{{ $unities['one']->UnidadTipo }}</p>
													</td>
													<td>
														<label>Nombre</label>
														<p class="help-block">{{ $unities['one']->UnidadNombre }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label>Nombre plural</label>
														<p class="help-block">{{ $unities['one']->UnidadNombrePlural }}</p>
													</td>
													<td>
														<label>Descripcion</label>
														<p class="help-block">{{ $unities['one']->UnidadDescripcion }}</p>
													</td>
													<td>
														<label>Estatus</label>
														<p class="help-block">{{ $unities['one']->RegistroCerrado == 0?'Cerrado':'Abierto' }}</p>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
				</div>
				{{-- $navigation['pagination'] --}}
			</div>
		</div>
	</div>
	{{-- Modal para filtrar --}}
	@include('panel.system.unity.shared.filter-modal')
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
    		app.scrollNavActions();

    		$('.panel-item').matchHeight({byRow: true});
    		$(document).tooltip({selector: '.is-tooltip'});
    	})();
    </script>
@endpush
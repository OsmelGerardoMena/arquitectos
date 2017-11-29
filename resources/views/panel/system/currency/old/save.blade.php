@extends('layouts.base')

{{-- Se registran los archivos css requeridos para esta sección --}}
@push('styles_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
@endpush

@section('content')
	{{--
		Menú de acciones 
		
		Se muestra el título de la sección actual
		y los botones acción

		<navbar-actions>
	--}}
	<div class="navbar-actions">
		<div class="container">
			<div class="navbar navbar-static-top navbar-inverse margin-bottom--clear">
				<div class="container-fluid">
					<div class="navbar-header">
						<p class="navbar-text">
							{{ $page['title'] }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
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
				</div>
			</div>
		</div>
	</div>
	{{-- </navbar-actions> --}}
	{{--
		
		Contenido de la sección
		
		Se mostra toda la lista de registro y la información de un registro
		seleccionado.

		<data-content>
	--}}
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
					<div class="panel-body padding-clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item">
								<form action="{{ $navigation['base'] }}/search" method="get">
									<div class="input-group">
										<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de moneda">
										<span class="input-group-btn">
									    	<button class="btn btn-default" type="submit">
									    		<span class="fa fa-search fa-fw"></span>
									    	</button>
									    </span>
									</div>
								</form>
							</div>
						</div>
						<form id="saveForm" action="{{ $navigation['base'] }}/action/save" method="POST" accept-charset="utf-8" class="col-sm-7 col-md-8 col-lg-9 panel-item">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="col-sm-12 margin-bottom--20 margin-top--20">
                                <div class="col-sm-12">
                                    @include('layouts.alerts', ['errors' => $errors])
                                </div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="cto34_name" class="form-label-full">Nombre</label>
										<input id="cto34_name"
											   name="cto34_name"
											   type="text"
											   value="{{ old('cto34_name')  }}"
											   class="form-control">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="cto34_abbreviation" class="form-label-full">Abreviatura</label>
										<input id="cto34_abbreviation"
											   name=cto34_abbreviation
											   type="text"
											   value="{{ old('cto34_abbreviation') }}"
											   class="form-control">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="cto34_symbol" class="form-label-full">Simbolo</label>
										<input id="cto34_symbol"
											   name="cto34_symbol"
											   type="text"
											   value="{{ old('cto34_symbol') }}"
											   class="form-control">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="cto34_fraction" class="form-label-full">Fracción</label>
										<input id="cto34_fraction"
											   name="cto34_fraction"
											   type="text"
											   value="{{ old('cto34_fraction') }}"
											   class="form-control">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
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
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
		<div class="container">
			<div class="navbar navbar-static-top navbar-inverse margin-bottom--clear">
				<div class="container-fluid">
					<div class="navbar-header">
						<p class="navbar-text">
							{{ $page['title'] }} / {{ $imss['one']->IMSSeguro }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="{{ $navigation['base'] }}/save" class="is-tooltip" data-placement="bottom" title="Nueva empresa">
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
								<a href="{{ $navigation['base'] }}/update/{{ $imss['one']->TbIMSSID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="is-tooltip" data-placement="bottom" title="Editar">
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
				</div>
			</div>
		</div>
	</div>
	{{-- </navbar-actions> --}}
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
						<div class="panel-body padding-clear">
							<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
								<div class="list-group-item">
									<form action="{{ $navigation['base'] }}/search" method="get">
										<div class="input-group">
											<input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de empresa">
											<span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
										    </span>
										</div>
									</form>
								</div>
								@foreach ($imss['all'] as $index => $b)
									<a href="{{ $navigation['base'] }}/info/{{ $b->TbIMSSID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item {{ ($b->TbIMSSID == $imss['one']->TbIMSSID) ? 'active' : '' }}">
										<h4 class="list-group-item-heading">{{ $b->	IMSSeguro }}</h4>
										<p class="small {{ ($b->TbIMSSID == $imss['one']->TbIMSSID) ? '' : 'text-muted' }}">
											{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
										</p>
									</a>
								@endforeach
							</div>
							<div>
								<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
									{{-- Formulario para eliminar un registro --}}
									@include('panel.system.imss.shared.delete-form')
									<h4 class="text-muted panel-section-title">IMSS</h4>
									<div class="table-responsive">
										<table class="table table-info">
											<tbody>
												<tr>
													<td>
														<label for="">Seguro</label>
														<p class="help-block">{{ $imss['one']->IMSSeguro }}</p>
													</td>
													<td>
														<label for="">Prestaciones</label>
														<p class="help-block">{{ $imss['one']->IMSSPrestaciones }}</p>
													</td>
													<td>
														<label for="">Cuota tipo</label>
														<p class="help-block">{{ $imss['one']->IMSSCuotaTipo }}</p>
													</td>
													<td>
														<label for="">Cuota trabajador</label>
														<p class="help-block">{{ $imss['one']->IMSSCuotaTrabajador }}</p>
													</td>
												</tr>
												<tr>
													<td>
														<label for="">Cuota patrón</label>
														<p class="help-block">{{ $imss['one']->IMSSCuotaTipo }}</p>
													</td>
													<td>
														<label for="">Base</label>
														<p class="help-block">{{ $imss['one']->IMSSBase }}</p>
													</td>
													<td>
														<label for="">Estatus</label>
														<p class="help-block">{{ $imss['one']->RegistroCerrado == 0?'Abierto':'Cerrado' }}</p>
													</td>
													<td>
		
													</td>
												</tr>
											</tbody>
										</table>
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
	@include('panel.system.imss.shared.filter-modal')
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
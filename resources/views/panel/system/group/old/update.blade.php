@extends('layouts.base')

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
							{{ $page['title'] }} / {{ $groups['one']->DirGrupoNombre }}
						</p>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li class="save">
								<a href="#" id="updateSubmitButton" class="is-tooltip" data-placement="bottom" title="Guardar">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
									</span>
								</a>
							</li>
							<li>
								<a href="{{ $navigation['base'] }}/save" class="is-tooltip confirm-leave" data-placement="bottom" title="Nuevo grupo">
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
									<a href="{{ $navigation['base'] }}/info/{{ $groups['one']->tbDirGrupoID }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
										<span class="fa-stack text-danger fa-lg">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-times fa-stack-1x fa-inverse"></i>
										</span>
									</a>
								@elseif ($navigation['from'] == 'search')
									<a href="{{ $navigation['base'] }}/search/info/{{ $groups['one']->tbDirGrupoID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
										<span class="fa-stack text-danger fa-lg">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-times fa-stack-1x fa-inverse"></i>
										</span>
									</a>
								@elseif ($navigation['from'] == 'filter')
									<a href="{{ $navigation['base'] }}/filter/info/{{ $groups['one']->tbDirGrupoID  }}{{ $navigation['query_string'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
										<span class="fa-stack text-danger fa-lg">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-times fa-stack-1x fa-inverse"></i>
										</span>
									</a>
								@endif
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
							@foreach ($groups['all'] as $index => $group)
								<a href="{{ $navigation['base'] }}/info/{{ $group->tbDirGrupoID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item {{ ($group->tbDirGrupoID == $groups['one']->tbDirGrupoID) ? 'active' : '' }}">
									<h4 class="list-group-item-heading">{{ $group->DirGrupoNombre }}</h4>
									<p class="small {{ ($group->tbDirGrupoID == $groups['one']->tbDirGrupoID) ? '' : 'text-muted' }}">
										{{ Carbon\Carbon::parse($group->created_at )->formatLocalized('%d %B %Y') }}
									</p>
								</a>
							@endforeach
							{{-- </data-list> --}}
						</div>
						<div>
							<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
								{{-- Formulario para eliminar un registro --}}
                                @include('panel.system.currency.shared.delete-form', [
                                        'title' => 'Moneda',
                                        'name' => $groups['one']->MonedaNombre,
                                        'id' => $groups['one']->tbDirGrupoID,
                                    ])
								<h4 class="text-muted panel-section-title">Grupo</h4>
								<form id="updateForm" action="{{ $navigation['base'] }}/action/update" method="POST" accept-charset="utf-8" class="col-sm-12">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="put">
									<input type="hidden" name="cto34_id" value="{{ $groups['one']->tbDirGrupoID }}">
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
                                                       value="{{ $groups['one']->DirGrupoNombre }}"
                                                       class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label for="cto34_description" class="form-label-full">Descripción</label>
												<input id="cto34_description"
                                                       name=cto34_description
                                                       type="text"
                                                       value="{{ $groups['one']->DirGrupoDescripcion }}"
                                                       class="form-control">
											</div>
										</div>
									</div>
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
    		app.scrollNavActions();

    		$('.panel-item').matchHeight({byRow: true});
    		$(document).tooltip({selector: '.is-tooltip'});
    	})();
    </script>
@endpush
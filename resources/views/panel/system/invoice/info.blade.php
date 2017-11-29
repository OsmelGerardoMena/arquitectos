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
                                        {{ $business['one']->EmpresaAlias }} <span class="fa fa-caret-right fa-fw"></span>
                                    </h4>
									<p class="small">
										{{ Carbon\Carbon::parse($business['one']->created_at )->formatLocalized('%d %B %Y') }}
									</p>
								</div>
								@foreach ($business['all'] as $index => $b)
									@if ($b->tbDirEmpresaID  == $business['one']->tbDirEmpresaID)
										@continue
									@endif
									<a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
										<h4 class="list-group-item-heading">{{ $b->EmpresaAlias }}</h4>
										<p class="small">
											{{ Carbon\Carbon::parse($b->created_at )->formatLocalized('%d %B %Y') }}
										</p>
									</a>
								@endforeach
							</div>
							<div>
								<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
									{{-- Formulario para eliminar un registro --}}
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
                                                <a href="{{ $navigation['base'] }}/update/{{ $business['one']->tbDirEmpresaID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="is-tooltip" data-placement="bottom" title="Editar">
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
										<table class="table table-info">
											<tbody>
												<tr>
													<td>
														<img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto">
													</td>
													<td colspan="2">
														<label for="">ID</label>
														<p class="help-block">{{ $business['one']->tbDirEmpresaID }}</p>
														<label for="">Alias</label>
														<p class="help-block">{{ $business['one']->EmpresaAlias }}</p>
														<label for="">Razón Social</label>
														<p class="help-block">{{ $business['one']->EmpresaRazonSocial }}</p>
														<label for="">Nombre Comercial</label>
														<p class="help-block">{{ $business['one']->EmpresaNombreComercial }}</p>
													</td>
													<tr>
														<td colspan="3">
															<hr>
														</td>
													</tr>
													<tr>
														<td>
															<label for="">Dependencia</label>
															<p class="help-block">{{ $business['one']->EmpresaDependencia }}</p>
														</td>
														<td>
															<label for="">Especialidad</label>
															<p class="help-block">{{ $business['one']->EmpresaEspecialidad }}</p>
														</td>
														<td>
															<label for="">Tipo de persona</label>
															<p class="help-block">{{ $business['one']->EmpresaTipoPersona }}</p>
														</td>
													</tr>
													<tr>
														<td>
															<label for="">Slogan</label>
															<p class="help-block">{{ $business['one']->EmpresaSlogan }}</p>
														</td>
														<td>
															<label for="">Página web</label>
															<p class="help-block">{{ $business['one']->EmpresaPaginaWeb }}</p>
														</td>
														<td>
															<label for="">RFC</label>
															<p class="help-block">{{ $business['one']->EmpresaRFC }}</p>
														</td>
													</tr>
													<tr>
														<td>
															<label for="">Número de IMSS</label>
															<p class="help-block">{{ $business['one']->EmpresaIMSSNumero }}</p>
														</td>
														<td>
															<label for="">Número de INFONAVIT</label>
															<p class="help-block">{{ $business['one']->EmpresaINFONAVITNumero }}</p>
														</td>
														<td>
															<label for="">RFC</label>
															<p class="help-block">{{ $business['one']->EmpresaSector }}</p>
														</td>
													</tr>
													<tr>
														<td colspan="3">
															<label for="">Comentarios</label>
															<p class="help-block">{{ $business['one']->EmpresaComentarios }}</p>
														</td>
													</tr>
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
	@include('panel.system.business.shared.filter-modal')
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
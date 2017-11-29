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
							<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item hidden-xs" style="position: relative;">
								<div class="list-group-item padding-clear padding-bottom--10">
									<form action="{{ $navigation['base'] }}/search" method="get">
										<div class="input-group input-group-sm">
											<input name="q" type="text" class="form-control form-control-plain" placeholder="Busqueda">
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
								@foreach ($business['all'] as $index => $b)
									@if ($b->tbDirEmpresaID  == $business['one']->tbDirEmpresaID)
										<a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item active">
											<h4 class="list-group-item-heading">
												{{ $b->EmpresaRazonSocial }}
											</h4>
											<p class="small">
												{{ $b->EmpresaNombreComercial }}
											</p>
										</a>
										@continue
									@endif
									<a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}#item{{ $contract->tbContratoID }}" class="list-group-item">
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
															<label for="cto34_alias">Alias</label>
															<input id="cto34_alias"
																   name="cto34_alias"
																   value="{{ $business['one']->EmpresaAlias }}"
																   readonly="readonly"
																   class="form-control form-control-plain form-control-ghost input-sm">
															<label for="cto34_legalName">Razón Social</label>
															<input id="cto34_legalName"
																   name="cto34_legalName"
																   value="{{ $business['one']->EmpresaRazonSocial }}"
																   readonly="readonly"
																   class="form-control form-control-plain form-control-ghost input-sm">
															<label for="cto34_commercialName">Nombre Comercial</label>
															<input id="cto34_commercialName"
																   name="cto34_commercialName"
																   value="{{ $business['one']->EmpresaNombreComercial }}"
																   readonly="readonly"
																   class="form-control form-control-plain form-control-ghost input-sm">
														</div>
														<div class="clearfix"></div>
														<div class="form-group col-sm-4">
															<label for="cto34_dependency" class="form-label-full">Dependencia</label>
															<input id="cto34_dependency"
																   name="cto34_dependency"
																   type="text"
																   value="{{ $business['one']->EmpresaDependencia }}"
																   readonly="readonly"
																   class="form-control form-control-plain form-control-ghost input-sm">
														</div>
														<div class="form-group col-sm-4">
															<label for="cto34_especiality" class="form-label-full">Especialidad</label>
															<input id="cto34_especiality"
																   name="cto34_especiality"
																   type="text"
																   value="{{ $business['one']->EmpresaEspecialidad }}"
																   readonly="readonly"
																   class="form-control form-control-plain form-control-ghost input-sm">
														</div>
														<div class="form-group col-sm-4 is-select">
															<label for="cto34_type" class="form-label-full">Tipo de persona</label>
															<p class="help-block">{{ $business['one']->EmpresaTipoPersona }}</p>
															<select id="cto34_type"
																	name="cto34_type"
																	class="form-control input-sm hidden">
																<optgroup label="Opción seleccionada">
																	<option value="{{ $business['one']->EmpresaTipoPersona }}">{{ $business['one']->EmpresaTipoPersona }}</option>
																</optgroup>
																<optgroup label="Opciones">
																	<option value="física">física</option>
																	<option value="moral">moral</option>
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
																   class="form-control form-control-plain form-control-ghost input-sm">
														</div>
														<div class="form-group col-sm-4">
															<label for="cto34_website" class="form-label-full">Página web</label>
															<input id="cto34_website"
																   name="cto34_website"
																   type="text"
																   value="{{ $business['one']->EmpresaPaginaWeb }}"
																   class="form-control form-control-plain form-control-ghost input-sm">
														</div>
														<div class="form-group col-sm-4">
															<label for="cto34_legalId" class="form-label-full">RFC</label>
															<input id="cto34_legalId"
																   name="cto34_legalId"
																   type="text"
                                                                   maxlength="25"
																   value="{{ $business['one']->EmpresaRFC }}"
																   class="form-control form-control-plain form-control-ghost input-sm">
														</div>
														<div class="clearfix"></div>
														<div class="form-group col-sm-4">
															<label for="cto34_imssNum" class="form-label-full">Número de IMSS</label>
															<input id="cto34_imssNum"
																   name="cto34_imssNum"
																   type="text"
																   value="{{ $business['one']->EmpresaIMSSNumero }}"
																   class="form-control form-control-plain form-control-ghost input-sm">
														</div>
														<div class="form-group col-sm-4">
															<label for="cto34_infonavitNum" class="form-label-full">Número de INFONAVIT</label>
															<input id="cto34_infonavitNum"
																   name="cto34_infonavitNum"
																   type="text"
																   value="{{ $business['one']->EmpresaINFONAVITNumero }}"
																   class="form-control form-control-plain form-control-ghost input-sm">
														</div>
														<div class="form-group col-sm-4 is-select">
															<label for="cto34_sector" class="form-label-full">Sector</label>
															<p class="help-block">{{ $business['one']->EmpresaSector }}</p>
															<select id="cto34_sector"
																	name="cto34_sector"
																	class="form-control form-control-plain form-control-ghost input-sm hidden">
																@if (!empty($business['one']->EmpresaSector ))
																	<optgroup label="Opción seleccionada">
																		<option value="{{ $business['one']->EmpresaSector }}">{{ $business['one']->EmpresaSector }}</option>
																	</optgroup>
																@endif
																<optgroup label="Opciones">
																	<option value="Privado">Privado</option>
																	<option value="Público">Público</option>
																</optgroup>
															</select>
														</div>
													</div>
												</div>
												<div role="tabpanel" class="tab-pane" id="addresses">
													@forelse($business['one']->addresses as $businessAddress)
														<div class="col-sm-12">
															<b>Domicilio completo</b><br>
															{{ $businessAddress->address->DirDomicilioCompleto }}
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
				</div>
			</div>
		</div>
		<div class="col-sm-12 margin-top--10 hidden-xs">
			{{ $navigation['pagination'] }}
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
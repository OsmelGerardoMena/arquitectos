@extends('layouts.base')

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
                @include('layouts.alerts', ['errors' => $errors])
            </div>
			<div class="col-sm-12">
				<div id="mainInfo"  class="panel panel-default panel-section">
					@if (count($business['all']) == 0)
						<div class="panel-body text-center">
							<h3>No hay empresas registradas</h3>
							<a href="{{ $navigation['base'].'/save' }}" class="btn btn-link btn-lg">Agregar empresa</a>
						</div>
					@else
						<div class="panel-body">
                            <div class="hidden-xs" style="position: absolute; width: 50%; top: 0; right: 0; z-index: 10">
                                <ul class="nav nav-actions navbar-nav navbar-right">
                                    <li>
                                        <a href="#"
                                           title="Nueva empresa"
                                           data-placement="bottom"
                                           data-toggle="modal"
                                           data-target="#modalNewRecord"
                                           class="is-tooltip" >
                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#"
                                           title="Filtrar"
                                           data-placement="bottom"
                                           data-toggle="modal"
                                           data-target="#modalFilter"
                                           class="is-tooltip">
                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-filter fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="btnItemSave is-tooltip disabled" data-row="0" data-placement="bottom" title="Guardar">
                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="btnItemEdit is-tooltip disabled" data-row="0" data-placement="bottom" title="Editar">
                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#"
                                           title="Eliminar"
                                           data-row="0"
                                           data-placement="bottom"
                                           data-toggle="modal"
                                           data-target="#modalDeleteRecord"
                                           class="btnItemDelete is-tooltip">
                                            <span class="fa-stack text-danger fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div id="itemsAside" class="col-sm-5 col-md-4 col-lg-3">
                                <form action="{{ $navigation['base'].'/save' }}" method="get" class="margin-bottom--10">
                                    <div class="input-group input-group-sm">
                                        <input name="q" type="text" class="form-control form-control-plain" placeholder="Busqueda">
                                        <span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
										    </span>
                                    </div>
                                </form>
                                <div id="itemsList" class="list-group margin-clear panel-item" role="tablist">
                                    @foreach ($business['all'] as $index => $b)
                                        @if ($index == 0)
                                            <a href="#item{{ $index }}"
                                                aria-controls="item{{ $index }}"
                                                role="tab"
                                                data-toggle="tab"
                                                data-row="{{ $index }}"
                                                class="list-group-item active hidden-xs">
                                                <h4 class="list-group-item-heading">
                                                    {{ $b->EmpresaRazonSocial }}
                                                </h4>
                                                <p class="small">
                                                    {{ $b->EmpresaNombreComercial }}
                                                </p>
                                            </a>
                                            <a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}"
                                                class="list-group-item active visible-xs">
                                                <h4 class="list-group-item-heading">
                                                    {{ $b->EmpresaAlias }} <span class="fa fa-caret-right fa-fw"></span>
                                                </h4>
                                                <p class="small">
                                                    {{ $b->EmpresaNombreComercial }}
                                                </p>
                                            </a>
                                            @continue
                                        @endif
                                            <a href="#item{{ $index }}"
                                                aria-controls="item{{ $index }}"
                                                role="tab"
                                                data-toggle="tab"
                                                data-row="{{ $index }}"
                                                class="list-group-item hidden-xs">
                                                <h4 class="list-group-item-heading">{{ $b->EmpresaRazonSocial }}</h4>
                                                <p class="small">
                                                    {{ $b->EmpresaNombreComercial }}
                                                </p>
                                            </a>
                                            <a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}"
                                                class="list-group-item visible-xs">
                                                <h4 class="list-group-item-heading">{{ $b->EmpresaRazonSocial }}</h4>
                                                <p class="text-muted small">
                                                    {{ $b->EmpresaNombreComercial }}
                                                </p>
                                            </a>
                                    @endforeach
                                </div>
                            </div>
                            <div id="itemsSection" class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear hidden-xs" style="padding-top: 20px">
                                <div id="itemsTabs" class="tab-content">
                                    @foreach ($business['all'] as $index => $businessOne)
                                        @if ($index == 0)
                                            <div role="tabpanel"  class="tab-pane active" id="item{{ $index }}">
                                                <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                                                    <li role="presentation" class="active">
                                                        <a href="#general{{ $index }}" aria-controls="home{{ $index }}" role="tab" data-toggle="tab">General</a>
                                                    </li>
                                                    <!--<li role="presentation">
                                                        <a href="#concepts{{ $index }}" aria-controls="profile{{ $index }}" role="tab" data-toggle="tab">Conceptos</a>
                                                    </li>-->
                                                    <li role="presentation">
                                                        <a href="#addresses{{ $index }}" aria-controls="profile{{ $index }}" role="tab" data-toggle="tab">Direcciones</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#comments{{ $index }}" aria-controls="messages{{ $index }}" role="tab" data-toggle="tab">Comentarios</a>
                                                    </li>
                                                </ul>
                                                <form id="itemForm{{ $index }}" action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8" class="cto_form row margin-top--10 padding-bottom--30">
                                                    <div class="tab-content col-sm-12">
                                                        <div role="tabpanel" class="tab-pane active" id="general{{ $index }}">
                                                            <div class="row">
                                                                <div class="form-group col-sm-4">
                                                                    <img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto">
                                                                </div>
                                                                <div class="form-group col-sm-8">
                                                                    <label for="cto34_alias{{ $index }}">Alias</label>
                                                                    <input id="cto34_alias{{ $index }}"
                                                                           name="cto34_alias"
                                                                           value="{{ $businessOne->EmpresaAlias }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                    <label for="cto34_legalName{{ $index }}">Razón Social</label>
                                                                    <input id="cto34_legalName{{ $index }}"
                                                                           name="cto34_legalName"
                                                                           value="{{ $businessOne->EmpresaRazonSocial }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                    <label for="cto34_commercialName{{ $index }}">Nombre Comercial</label>
                                                                    <input id="cto34_commercialName{{ $index }}"
                                                                           name="cto34_commercialName"
                                                                           value="{{ $businessOne->EmpresaNombreComercial }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_dependency{{ $index }}" class="form-label-full">Dependencia</label>
                                                                    <input id="cto34_dependency{{ $index }}"
                                                                           name="cto34_dependency"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaDependencia }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_especiality{{ $index }}" class="form-label-full">Especialidad</label>
                                                                    <input id="cto34_especiality{{ $index }}"
                                                                           name="cto34_especiality"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaEspecialidad }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4 is-select">
                                                                    <label for="cto34_type{{ $index }}" class="form-label-full">Tipo de persona</label>
                                                                    <p class="help-block">{{ $businessOne->EmpresaTipoPersona }}</p>
                                                                    <select id="cto34_type{{ $index }}"
                                                                            name="cto34_type"
                                                                            class="form-control input-sm hidden">
                                                                        <optgroup label="Opción seleccionada">
                                                                            <option value="{{ $businessOne->EmpresaTipoPersona }}">{{ $businessOne->EmpresaTipoPersona }}</option>
                                                                        </optgroup>
                                                                        <optgroup label="Opciones">
                                                                            <option value="física">física</option>
                                                                            <option value="moral">moral</option>
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_slogan{{ $index }}" class="form-label-full">Slogan</label>
                                                                    <input id="cto34_slogan{{ $index }}"
                                                                           name="cto34_slogan"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaSlogan }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_website{{ $index }}" class="form-label-full">Página web</label>
                                                                    <input id="cto34_website{{ $index }}"
                                                                           name="cto34_website"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaPaginaWeb }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_legalId{{ $index }}" class="form-label-full">RFC</label>
                                                                    <input id="cto34_legalId{{ $index }}"
                                                                           name="cto34_legalId"
                                                                           maxlength="25"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaRFC }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_imssNum{{ $index }}" class="form-label-full">Número de IMSS</label>
                                                                    <input id="cto34_imssNum{{ $index }}"
                                                                           name="cto34_imssNum"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaIMSSNumero }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_infonavitNum{{ $index }}" class="form-label-full">Número de INFONAVIT</label>
                                                                    <input id="cto34_infonavitNum{{ $index }}"
                                                                           name="cto34_infonavitNum"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaINFONAVITNumero }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4 is-select">
                                                                    <label for="cto34_sector{{ $index }}" class="form-label-full">Sector</label>
                                                                    <p class="help-block">{{ $businessOne->EmpresaSector }}</p>
                                                                    <select id="cto34_sector{{ $index }}"
                                                                            name="cto34_sector"
                                                                            class="form-control form-control-plain form-control-ghost input-sm hidden">
                                                                        @if (!empty($businessOne->EmpresaSector ))
                                                                            <optgroup label="Opción seleccionada">
                                                                                <option value="{{ $businessOne->EmpresaSector }}">{{ $businessOne->EmpresaSector }}</option>
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
                                                        <!--<div role="tabpanel" class="tab-pane" id="concepts{{ $index }}">

                                                        </div>--->
                                                        <div role="tabpanel" class="tab-pane" id="addresses{{ $index }}">
                                                            @forelse($businessOne->addresses as $businessAddress)
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
                                                        <div role="tabpanel" class="tab-pane" id="comments{{ $index }}">

                                                        </div>
                                                    </div>
                                                    <input name="cto34_id" type="hidden" value="{{ $businessOne->tbDirEmpresaID }}">
                                                    <input name="_item" type="hidden" value="#item{{ $index }}">
                                                    <input name="_method" type="hidden" value="put">
                                                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                                </form>
                                            </div>
                                            @continue
                                        @endif
                                            <div role="tabpanel"  class="tab-pane" id="item{{ $index }}">
                                                <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                                                    <li role="presentation" class="active">
                                                        <a href="#general{{ $index }}" aria-controls="home{{ $index }}" role="tab" data-toggle="tab">General</a>
                                                    </li>
                                                <!--<li role="presentation">
                                                        <a href="#concepts{{ $index }}" aria-controls="profile{{ $index }}" role="tab" data-toggle="tab">Conceptos</a>
                                                    </li>-->
                                                    <li role="presentation">
                                                        <a href="#addresses{{ $index }}" aria-controls="profile{{ $index }}" role="tab" data-toggle="tab">Direcciones</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#comments{{ $index }}" aria-controls="messages{{ $index }}" role="tab" data-toggle="tab">Comentarios</a>
                                                    </li>
                                                </ul>
                                                <form id="itemForm{{ $index }}" action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8" class="cto_form row margin-top--10 padding-bottom--30">
                                                    <div class="tab-content col-sm-12">
                                                        <div role="tabpanel" class="tab-pane active" id="general{{ $index }}">
                                                            <div class="row">
                                                                <div class="form-group col-sm-4">
                                                                    <img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto">
                                                                </div>
                                                                <div class="form-group col-sm-8">
                                                                    <label for="cto34_alias{{ $index }}">Alias</label>
                                                                    <input id="cto34_alias{{ $index }}"
                                                                           name="cto34_alias"
                                                                           value="{{ $businessOne->EmpresaAlias }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                    <label for="cto34_legalName{{ $index }}">Razón Social</label>
                                                                    <input id="cto34_legalName{{ $index }}"
                                                                           name="cto34_legalName"
                                                                           value="{{ $businessOne->EmpresaRazonSocial }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                    <label for="cto34_commercialName{{ $index }}">Nombre Comercial</label>
                                                                    <input id="cto34_commercialName{{ $index }}"
                                                                           name="cto34_commercialName"
                                                                           value="{{ $businessOne->EmpresaNombreComercial }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_dependency{{ $index }}" class="form-label-full">Dependencia</label>
                                                                    <input id="cto34_dependency{{ $index }}"
                                                                           name="cto34_dependency"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaDependencia }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_especiality{{ $index }}" class="form-label-full">Especialidad</label>
                                                                    <input id="cto34_especiality{{ $index }}"
                                                                           name="cto34_especiality"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaEspecialidad }}"
                                                                           readonly="readonly"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4 is-select">
                                                                    <label for="cto34_type{{ $index }}" class="form-label-full">Tipo de persona</label>
                                                                    <p class="help-block">{{ $businessOne->EmpresaTipoPersona }}</p>
                                                                    <select id="cto34_type{{ $index }}"
                                                                            name="cto34_type"
                                                                            class="form-control input-sm hidden">
                                                                        <optgroup label="Opción seleccionada">
                                                                            <option value="{{ $businessOne->EmpresaTipoPersona }}">{{ $businessOne->EmpresaTipoPersona }}</option>
                                                                        </optgroup>
                                                                        <optgroup label="Opciones">
                                                                            <option value="física">física</option>
                                                                            <option value="moral">moral</option>
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_slogan{{ $index }}" class="form-label-full">Slogan</label>
                                                                    <input id="cto34_slogan{{ $index }}"
                                                                           name="cto34_slogan"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaSlogan }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_website{{ $index }}" class="form-label-full">Página web</label>
                                                                    <input id="cto34_website{{ $index }}"
                                                                           name="cto34_website"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaPaginaWeb }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_legalId{{ $index }}" class="form-label-full">RFC</label>
                                                                    <input id="cto34_legalId{{ $index }}"
                                                                           name="cto34_legalId"
                                                                           maxlength="25"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaRFC }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_imssNum{{ $index }}" class="form-label-full">Número de IMSS</label>
                                                                    <input id="cto34_imssNum{{ $index }}"
                                                                           name="cto34_imssNum"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaIMSSNumero }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label for="cto34_infonavitNum{{ $index }}" class="form-label-full">Número de INFONAVIT</label>
                                                                    <input id="cto34_infonavitNum{{ $index }}"
                                                                           name="cto34_infonavitNum"
                                                                           type="text"
                                                                           value="{{ $businessOne->EmpresaINFONAVITNumero }}"
                                                                           class="form-control form-control-plain form-control-ghost input-sm">
                                                                </div>
                                                                <div class="form-group col-sm-4 is-select">
                                                                    <label for="cto34_sector{{ $index }}" class="form-label-full">Sector</label>
                                                                    <p class="help-block">{{ $businessOne->EmpresaSector }}</p>
                                                                    <select id="cto34_sector{{ $index }}"
                                                                            name="cto34_sector"
                                                                            class="form-control form-control-plain form-control-ghost input-sm hidden">
                                                                        @if (!empty($businessOne->EmpresaSector ))
                                                                            <optgroup label="Opción seleccionada">
                                                                                <option value="{{ $businessOne->EmpresaSector }}">{{ $businessOne->EmpresaSector }}</option>
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
                                                    <!--<div role="tabpanel" class="tab-pane" id="concepts{{ $index }}">

                                                        </div>--->
                                                        <div role="tabpanel" class="tab-pane" id="addresses{{ $index }}">
                                                            <div class="row">
                                                                @forelse($businessOne->addresses as $businessAddress)
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

                                                        </div>
                                                        <div role="tabpanel" class="tab-pane" id="comments{{ $index }}">

                                                        </div>
                                                    </div>
                                                    <input name="cto34_id" type="hidden" value="{{ $businessOne->tbDirEmpresaID }}">
                                                    <input name="_item" type="hidden" value="#item{{ $index }}">
                                                    <input name="_method" type="hidden" value="put">
                                                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                                </form>
                                            </div>
                                    @endforeach
                                </div>
                            </div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
	{{-- </data-content> --}}
    {{--
        Modal
        Se llama a la vista compartida de modals

        <modals>
    --}}
	@include('panel.system.business.shared.modals')
    {{-- </modals> --}}
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script>
    	(function() {

    		var app = new App();
    		//app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
            app.tooltip();



            var mainInfo = $('#mainInfo');
            var windowHeight = $(window).height();

            mainInfo.height( windowHeight - 110);

            var itemsList = $('#itemsList');
            var itemsTabs = $('#itemsTabs');

            if (window.location.hash) {
                var currentHash = window.location.hash
                itemsList.find('a').removeClass('active');

                $('a[href="' + currentHash +'"]').tab('show');
                itemsList.find('a[href="' + currentHash + '"]').addClass('active');
                history.pushState('', document.title, window.location.pathname);
            }

            $(itemsList).css('padding-bottom', itemsList.height() / 2);

            if ($(itemsList).find('a:last-child').is(':hover')) {
                console.log('is over!!');
                $('body').css('height', windowHeight).css('overflow', 'hidden');
            }

            $(itemsList).on('scroll mouseenter mouseover', function () {
                enableBodyScroll()
            });

            $(itemsList).on('mouseleave', function () {
                disableBodyScroll()
            });

            $(itemsTabs).on('scroll mouseenter mouseover', function () {
                enableBodyScroll()
            });

            $(itemsTabs).on('mouseleave', function () {
                disableBodyScroll()
            });

            $(itemsList.children('a')).click(function (e) {

                if ($(this).hasClass('hidden-xs')) {
                    e.preventDefault();
                }

                var row = $(this).data('row');
                var buttonSave = $('.btnItemSave');
                var buttonUpdate = $('.btnItemEdit');
                var buttonDelete = $('.btnItemDelete');
                var form = $('#itemForm' + row);


                buttonSave.attr('data-row', row)
                    .addClass('disabled');
                buttonUpdate.attr('data-row', row)
                    .addClass('disabled');
                buttonDelete.attr('data-row', row);

                buttonSave.addClass('disabled');
                buttonUpdate.addClass('disabled');

                form[0].reset();
                form.find('input').addClass('form-control-ghost');
                form.find('input').prop('readonly', 'readonly');
                form.find('.is-select').find('p').removeClass('hidden').addClass('visible');
                form.find('.is-select').find('select').removeClass('visible').addClass('hidden');

                itemsList.children('a').removeClass('active');

                var tab = $(this);
                $(tab).addClass('active');
                $(tab).tab('show');
            });

            $('.btnItemSave').on('click', function(e) {

                e.preventDefault();

                var button = $(this);
                var row = button.attr('data-row');
                var form = $('#itemForm' + row);

                $('.is-tooltip').tooltip('hide');

                if (button.hasClass('disabled')) {
                    button.blur();
                    return false;
                }

                var defaultHtml = '<span class="fa-stack fa-lg">';
                defaultHtml += '<i class="fa fa-circle fa-stack-2x"></i>';
                defaultHtml += '<i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>';
                defaultHtml += '</span>';

                var loadingHtml = '<span class="fa-stack fa-lg">';
                loadingHtml += '<i class="fa fa-circle fa-stack-2x"></i>';
                loadingHtml += '<i class="fa fa-spinner fa-spin  fa-stack-1x fa-inverse"></i>';
                loadingHtml += '</span>';

                button.html(loadingHtml);
                button.blur();

                var request = $.ajax({
                    url: form.prop('action'),
                    type: form.prop('method'),
                    dataType: 'html',
                    timeout: 90000,
                    cache: false,
                    data: form.serialize()
                });

                request.done(function(response) {

                    var result = jQuery.parseJSON(response);

                    console.log(result);

                    if (result.status === false) {



                        $.each(result.errors.all, function(key, error) {

                            var input = form.find('input[name="' + key + '"]');
                            var select = form.find('select[name="' + key + '"]');
                            var textarea = form.find('textarea[name="' + key + '"]');

                            if (input.length > 0) {
                                input.css('border-color', '#ef5350');
                            }

                            if (select.length > 0) {
                                select.css('border-color', '#ef5350');
                            }

                            if (textarea.length > 0) {
                                textarea.css('border-color', '#ef5350');
                            }
                        });

                        button.html(defaultHtml);
                        button.prop('disabled', false);
                        alert(result.errors.first);

                    } else {

                        button.html(defaultHtml);
                        alert('Registro actualizado correctamente');

                        window.location.href += '#item' + row;
                        location.reload();
                    }
                });

                request.fail(function(xhr, textStatus) {
                    button.html(defaultHtml);
                    button.prop('disabled', false);
                    alert('Error: ' + textStatus);
                });

            });

            $('.btnItemEdit').on('click', function(e) {

                e.preventDefault();

                var button = $(this);
                var row = $(this).attr('data-row');
                var form = $('#itemForm' + row);
                var buttonSave = $('.btnItemSave[data-row="' + row + '"]');

                if (button.hasClass('disabled')) {
                    buttonSave.removeClass('disabled');
                    button.removeClass('disabled');
                    form.find('input, select').removeClass('form-control-ghost');
                    form.find('input').removeAttr('readonly');
                    form.find('.is-select').find('p').addClass('hidden');
                    form.find('.is-select').find('select').removeClass('hidden').addClass('visible');

                } else {

                    form[0].reset();
                    buttonSave.addClass('disabled');
                    button.addClass('disabled');
                    form.find('input, select, textarea').addClass('form-control-ghost');
                    form.find('input').prop('readonly', 'readonly');
                    form.find('.is-select').find('p').removeClass('hidden').addClass('visible');
                    form.find('.is-select').find('select').removeClass('visible').addClass('hidden');
                }

                button.focusout();
                button.off('blur');
            });

            var modalDeleteRecord = $('#modalDeleteRecord');

            modalDeleteRecord.on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget);
                var row = button.attr('data-row');
                var form = $('#itemForm' + row);
                var id = form.find('input[name="cto34_id"]');
                var name = form.find('input[name="cto34_alias"]');

                button.one('focus', function(){$(this).blur();});
                console.log(row);

                var modal = $(this);
                modal.find('#recordDeleteId').val(id.val());
                modal.find('#recordDeleteName').text(name.val());
            });

            modalDeleteRecord.on('hide.bs.modal', function () {

                var modal = $(this);
                modal.find('#recordDeleteId').val('');
                modal.find('#recordDeleteName').text('');
            });

    	})();

        function enableBodyScroll() {
            var windowHeight = $(window).height();
            $('body').css('height', windowHeight).css('overflow', 'hidden');
        }

    	function disableBodyScroll() {
            $('body').css('height', '100%').css('overflow', 'auto');
        }

    </script>
@endpush
@if($customers['all']->count() == 0)
    <div class="panel-body padding-top--clear">
        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
            <div class="list-group-item padding-clear padding-bottom--5">
                <div class="input-group input-group-sm">
                    <input id="search" name="q" class="form-control form-control-plain" placeholder="Busqueda" disabled>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" disabled>
                            <span class="fa fa-search fa-fw"></span>
                        </button>
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalFilter" disabled>
                            <span class="fa fa-filter fa-fw"></span>
                        </button>
                    </span>
                </div>
            </div>
            <div id="itemsList">
                <div id="item0" class="list-group-item">
                    <h4 class="list-group-item-heading">
                        Sin registros
                    </h4>
                    <p class="text-muted small">
                        {{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}
                    </p>
                </div>
            </div>
            <div class="list-group-item padding-clear padding-top--10">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        {{--
                            Pagination
                            Se muestra datos y botones de paginación
                        --}}
                        @include('shared.pagination')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
            <div class="col-sm-12 margin-bottom--5">
                <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                            General
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row" id="general">
                    <div class="col-sm-12 text-center">
                        <h4>No hay datos.</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="panel-body padding-top--clear">
        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
            <div class="list-group-item padding-clear padding-bottom--5">
                {{--
                    Content Search
                    Se incluyen la forma para la busqueda y filtrado de datos
                --}}
                @include('panel.system.user.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($customers['all'] as $index => $customer)

                    @if ($customer->TbClientesID == $customers['one']->TbClientesID)
                        <div id="item{{ $customer->TbClientesID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $customer->business->EmpresaRazonSocial }}</h4>
                            <p class="small">
                                {{ $customer->ClienteDependencia }}
                            </p>
                        </div>
                        @continue
                    @endif

                    <a id="item{{ $customer->TbClientesID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $customer->TbClientesID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $customer->business->EmpresaRazonSocial }}</h4>
                        <p class="text-muted small">
                            {{ $customer->ClienteDependencia }}
                        </p>
                    </a>
                @endforeach
            </div>
            <div class="list-group-item padding-clear padding-top--10">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        {{--
                            Pagination
                            Se muestra datos y botones de paginación
                        --}}
                        @include('shared.pagination')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
            <div class="col-sm-12 margin-bottom--5">
                <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                            General
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                    <div class="form-group col-sm-6 col-sm-offset-6">
                        <label class="form-label-full">Fecha de alta</label>
                        <p class="help-block">
                            {{ Carbon\Carbon::parse($customers['one']->ClienteFechaAlta )->formatLocalized('%A %d %B %Y') }}
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <div class="form-label-full">Empresa</div>
                        <p class="help-block">{{ $customers['one']->business->EmpresaRazonSocial }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Dependencia</label>
                        <p class="help-block">
                            {{ ifempty($customers['one']->ClienteDependencia) }}
                        </p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Sector</label>
                        <p class="help-block">
                            {{ ifempty($customers['one']->ClienteSector) }}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <div class="form-label-full">Representante</div>
                        <p class="help-block">
                            @if (!empty($customers['one']->person))
                                {{ $customers['one']->person->PersonaNombreCompleto }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Cargo</label>
                        <p class="help-block">
                            {{ ifempty($customers['one']->ClienteRepresentanteCargo) }}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Forma de pago</label>
                        <p class="help-block">
                            {{ ifempty($customers['one']->ClienteFormaDePago) }}
                        </p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Cuenta</label>
                        <p class="help-block">
                            {{ ifempty($customers['one']->ClienteCuentaDePago) }}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Número de proveedor</label>
                        <p class="help-block">
                            {{ ifempty($customers['one']->ClienteProveedorNumero) }}
                        </p>
                    </div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($customers['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $customers['one']->getKey() }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $customers['one']->getKey() }}" disabled>
                                @endif
                            @else
                                @if (!empty($customers['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $customers['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $customers['one']->getKey() }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $customers['one']->getKey() }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $customers['one']->getKey() }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    <input type="hidden" name="_recordId" value="{{ $customers['one']->getKey() }}">
@endif
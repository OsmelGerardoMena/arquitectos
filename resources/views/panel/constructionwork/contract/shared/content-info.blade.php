@if($contracts['all']->count() == 0)
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
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Firmas
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Fechas
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Garantías
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Domicilios
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Detalles
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#" >
                            Entregables
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Catálogos
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Estimaciones
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Comentarios
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
                @include('panel.constructionwork.contract.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($contracts['all'] as $index => $contract)
                    @if ($contracts['one']->tbContratoID == $contract->tbContratoID)
                        <div id="item{{ $contract->tbContratoID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $contract->ContratoAlias }}</h4>
                            <p class="small">
                                {{ $contract->ContratoAlcanceCorto }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $contract->tbContratoID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $contract->tbContratoID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $contract->ContratoAlias }}</h4>
                        <p class="text-muted small">
                            {{ $contract->ContratoAlcanceCorto }}
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
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab"  data-type="own">
                            General
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#signatures" aria-controls="signatures" role="tab" data-toggle="tab"  data-type="own">
                            Firmas
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#dates" aria-controls="dates" role="tab" data-toggle="tab"  data-type="own">
                            Fechas
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#guarantees" aria-controls="guarantees" role="tab" data-toggle="tab"  data-type="own">
                            Garantías
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#address" aria-controls="address" role="tab" data-toggle="tab"  data-type="own">
                            Domicilios
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#details" aria-controls="details" role="tab" data-toggle="tab"  data-type="own">
                            Detalles
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#deliverables" aria-controls="deliverables" role="tab" data-toggle="tab"  data-type="relation" data-element="#modalSaveContractDeliverables">
                            Entregables
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Catálogos
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Estimaciones
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#comments" aria-controls="general" role="tab" data-toggle="tab" data-type="relation" data-element="#modalComment">
                            Comentarios
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane row active" id="general">
                    <div class="col-sm-12 form-group">
                        <label class="form-label-full">Alias</label>
                        <p class="help-block">{{ $contracts['one']->ContratoAlias  }}</p>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Contratista</label>
                        @if(!empty($contracts['one']->contractor))
                            <p class="help-block">
                                <a href="#" data-id="{{ $contracts['one']->TbDirEmpresaObraID_Contratista }}" data-toggle="modal" data-target="#showBusinessModal">
                                    {{ $contracts['one']->contractor->business[0]->EmpresaAlias  }}
                                </a>
                            </p>
                        @endif
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Modalidad</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoModalidad)  }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Tipo de acuerdo</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoTipo)  }}</p>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Contrato original</label>
                        <p class="help-block">{{ ifempty($contracts['one']->tbContratosID_ContratoOriginal)  }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Número</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoNumero, '0')  }}</p>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Tipo de asignación</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoAsignacionTipo)  }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Importe sin IVA</label>
                        <p class="help-block">${{ number_format($contracts['one']->ContratoImporteContratado, 2)  }}</p>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Moneda</label>
                        <p class="help-block">
                            @if (!empty($contracts['one']->currency))
                                {{ $contracts['one']->currency->MonedaAbreviatura }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <label class="form-label-full">Cliente directo</label>
                        @if (!empty($contracts['one']->directCustomer))
                            @if (!empty($contracts['one']->directCustomer->business[0]))
                                <p class="help-block">
                                    <a href="#" data-id="{{  $contracts['one']->tbDirEmpresaObraID_ClienteDirecto }}" data-toggle="modal" data-target="#showBusinessModal">
                                        {{ $contracts['one']->directCustomer->business[0]->EmpresaRazonSocial  }}
                                    </a>
                                </p>
                            @endif
                        @endif
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Cliente contratante</label>
                        @if (!empty($contracts['one']->contractCustomer))
                            @if (!empty($contracts['one']->contractCustomer->business[0]))
                                <p class="help-block">
                                    <a href="#" data-id="{{  $contracts['one']->tbDirEmpresaObraID_ClienteContratante }}" data-toggle="modal" data-target="#showBusinessModal">
                                        {{ $contracts['one']->contractCustomer->business[0]->EmpresaRazonSocial  }}
                                    </a>
                                </p>
                            @endif
                        @endif
                    </div>
                    <div class="col-sm-12 form-group">
                        <label class="form-label-full">Objeto del contrato</label>
                        <p class="help-block help-block--textarea">{{ $contracts['one']->ContratoObjeto }}</p>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Alcance corto</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoAlcanceCorto) }}</p>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Empresa supervisora</label>
                        @if (!empty($contracts['one']->supervisingCompany))
                            @if (!empty($contracts['one']->supervisingCompany->business[0]))
                                <p class="help-block">
                                    <a href="#" data-id="{{  $contracts['one']->tbDirEmpresaObraID_ContratoSupervisora }}" data-toggle="modal" data-target="#showBusinessModal">
                                        {{ $contracts['one']->supervisingCompany->business[0]->EmpresaAlias }}
                                    </a>
                                </p>
                            @else
                                <p class="help-block"> - </p>
                            @endif
                        @else
                            <p class="help-block">
                                -
                            </p>
                        @endif
                    </div>
                    <div class="col-sm-12 form-group text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($contracts['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $contracts['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $contracts['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($contracts['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $contracts['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $contracts['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $contracts['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $contracts['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="signatures">
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Firma por el cliente</label>
                        @if (!empty($contracts['one']->customerSignature))
                            @if (!empty($contracts['one']->customerSignature->persons[0]))
                                <p class="help-block">
                                    <a href="#" data-id="{{  $contracts['one']->TbDirPersonaObraID_FirmaCliente }}" data-toggle="modal" data-target="#showPersonModal">
                                        {{ $contracts['one']->customerSignature->persons[0]->PersonaNombreCompleto }}
                                    </a>
                                </p>
                            @else
                                <p class="help-block">
                                    -
                                </p>
                            @endif
                        @else
                            <p class="help-block">
                                -
                            </p>
                        @endif
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Repr.del cliente en obra</label>
                        @if (!empty($contracts['one']->customerRepresentative))
                            @if (!empty($contracts['one']->customerRepresentative->persons[0]))
                                <p class="help-block">
                                    <a href="#" data-id="{{  $contracts['one']->tbDirPersonaEmpresaObraID_ClienteRepresentante }}" data-toggle="modal" data-target="#showPersonModal">
                                        {{ $contracts['one']->customerRepresentative->persons[0]->PersonaNombreCompleto }}
                                    </a>
                                </p>
                            @else
                                <p class="help-block">
                                    -
                                </p>
                            @endif
                        @else
                            <p class="help-block">
                                -
                            </p>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Firma por el contratista</label>
                        @if (!empty($contracts['one']->contractorSignature))
                            @if (!empty($contracts['one']->contractorSignature->persons[0]))
                                <p class="help-block">
                                    <a href="#" data-id="{{  $contracts['one']->tbDirPersonaEmpresaObraID_FirmaContratista }}" data-toggle="modal" data-target="#showPersonModal">
                                        {{ $contracts['one']->contractorSignature->persons[0]->PersonaNombreCompleto }}
                                    </a>
                                </p>
                            @else
                                <p class="help-block">
                                    -
                                </p>
                            @endif
                        @else
                            <p class="help-block">
                                -
                            </p>
                        @endif
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Reponsable en obra</label>
                        @if (!empty($contracts['one']->workManager))
                            @if (!empty($contracts['one']->workManager->persons[0]))
                                <p class="help-block">
                                    <a href="#" data-id="{{  $contracts['one']->TbDirPersonaObraID_ContratistaResponsableObra }}" data-toggle="modal" data-target="#showPersonModal">
                                        {{ $contracts['one']->workManager->persons[0]->PersonaNombreCompleto }}
                                    </a>
                                </p>
                            @else
                                <p class="help-block">
                                    -
                                </p>
                            @endif
                        @else
                            <p class="help-block">
                                -
                            </p>
                        @endif
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="dates">
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Fecha firma</label>
                        <p class="help-block">
                            @if ($contracts['one']->ContratoFechaFirma != '0000-00-00 00:00:00' && !empty($contracts['one']->ContratoFechaFirma))
                                {{ Carbon\Carbon::parse($contracts['one']->ContratoFechaFirma )->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Inicio contrato</label>
                        <p class="help-block">
                            @if ($contracts['one']->ContratoFechaTerminoContrato != '0000-00-00 00:00:00' && !empty($contracts['one']->ContratoFechaTerminoContrato))
                                {{ Carbon\Carbon::parse($contracts['one']->ContratoFechaInicioContrato )->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Término contrato</label>
                        <p class="help-block">
                            @if ($contracts['one']->ContratoFechaTerminoContrato != '0000-00-00 00:00:00' && !empty($contracts['one']->ContratoFechaTerminoContrato))
                                {{ Carbon\Carbon::parse($contracts['one']->ContratoFechaTerminoContrato )->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <?php

                        $contractStartDate = Carbon\Carbon::parse($contracts['one']->ContratoFechaInicioContrato );
                        $contractEndDate = Carbon\Carbon::parse($contracts['one']->ContratoFechaTerminoContrato);

                        $contractDuration =  $contractStartDate->diffInDays($contractEndDate);
                        ?>
                        <label class="form-label-full">Duración contrato</label>
                        <p class="help-block">{{ $contractDuration }} días</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Inicio real</label>
                        <p class="help-block">
                            @if ($contracts['one']->ContratoFechaInicioReal != '0000-00-00 00:00:00' && !empty($contracts['one']->ContratoFechaInicioReal))
                                {{ Carbon\Carbon::parse($contracts['one']->ContratoFechaInicioReal)->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Término real</label>
                        <p class="help-block">
                            @if ($contracts['one']->ContratoFechaTerminoReal != '0000-00-00 00:00:00' && !empty($contracts['one']->ContratoFechaTerminoReal))
                                {{ Carbon\Carbon::parse($contracts['one']->ContratoFechaTerminoReal)->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <?php

                        $contractRealStartDate = Carbon\Carbon::parse($contracts['one']->ContratoFechaInicioReal );
                        $contractRealEndDate = Carbon\Carbon::parse($contracts['one']->ContratoFechaTerminoReal);

                        $contractRealDuration =  $contractRealStartDate->diffInDays($contractRealEndDate);

                        ?>
                        <label class="form-label-full">Duración real</label>
                        <p class="help-block">{{ $contractRealDuration }} días</p>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="guarantees">
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Anticipo %</label>
                        <p class="help-block">{{ $contracts['one']->ContratoAnticipoPCT  }}%</p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Anticipo monto</label>
                        <p class="help-block">{{ $contracts['one']->ContratoAnticipoMonto  }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Fondo garantía %</label>
                        <p class="help-block">{{ $contracts['one']->ContratoFondoGarantiaPCT  }}%</p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Fondo garantía monto</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoFondoGarantiaMonto, '0')  }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Otras retenciones %</label>
                        <p class="help-block">{{ $contracts['one']->ContratoOtrasRetencionesPCT  }}%</p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Otras retenciones monto</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoOtrasRetencionesMonto, '0')  }}</p>
                    </div>
                    <div class="col-sm-12 form-group">
                        <label class="form-label-full">Otras retenciones concepto</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoOtrasRetencionesConcepto)  }}</p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Fianza anticipo</label>
                        <p class="help-block">{{ $contracts['one']->ContratoFianzaAnticipoPCT  }}%</p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Fianza garantía</label>
                        <p class="help-block">{{ $contracts['one']->ContratoFianzaGarantiaPCT  }}%</p>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label class="form-label-full">Fianza vicios ocultos</label>
                        <p class="help-block">{{ $contracts['one']->ContratoFianzaViciosOcultosPCT  }}%</p>
                    </div>
                    <div class="col-sm-12 form-group">
                        <label class="form-label-full">Otras fianzas y garantías</label>
                        <p class="help-block">{{ ifempty($contracts['one']->ContratoOtrasFianzas)  }}</p>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="address">
                    <div class="col-sm-12 form-group">
                        <label class="form-label-full">Domicilio cliente</label>
                        @if (!empty($contracts['one']->clientAddress))
                            @if (!empty($contracts['one']->clientAddress->address))
                                <p class="help-block help-block--textarea">{{ $contracts['one']->clientAddress->address->DirDomicilioCompleto }}</p>
                            @else
                                <p class="help-block help-block--textarea"> - </p>
                            @endif
                        @else
                            <p class="help-block help-block--textarea"> - </p>
                        @endif
                    </div>
                    <div class="col-sm-12 form-group">
                        <label class="form-label-full">Domicilio contratista</label>
                        @if (!empty($contracts['one']->contractorAddress))
                            @if (!empty($contracts['one']->contractorAddress->address))
                                <p class="help-block help-block--textarea">{{ $contracts['one']->contractorAddress->address->DirDomicilioCompleto }}</p>
                            @else
                                <p class="help-block help-block--textarea"> - </p>
                            @endif
                        @else
                            <p class="help-block help-block--textarea"> - </p>
                        @endif
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="details">
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Creado por</label>
                        <p class="help-block"> - </p>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Creado timestamp</label>
                        <p class="help-block">{{ Carbon\Carbon::parse($contracts['one']->ContratoCreadoTimestamp )->formatLocalized('%A %d %B %Y')  }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Modificado por</label>
                        <p class="help-block"> - </p>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label-full">Modificado timestamp</label>
                        <p class="help-block">
                            @if (!empty($contracts['one']->ContratoModificadoTimestamp) && $contracts['one']->ContratoModificadoTimestamp != '0000-00-00 00:00:00')
                                {{ Carbon\Carbon::parse($contracts['one']->ContratoModificadoTimestamp)->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="deliverables">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4"><b>Título</b></div>
                            <div class="col-sm-4"><b>Plazo</b></div>
                            <div class="col-sm-4"><b>Estatus</b></div>
                            <div class="col-sm-12 margin-top--5 margin-bottom--5">
                                <hr class="margin-clear">
                            </div>
                        </div>
                        <div class="items-relation">
                            @forelse($contracts['one']->deliverables as $deliverable)
                                <a href="#" data-id="{{ $deliverable->tbContratoEntregableID  }}" data-toggle="modal" data-target="#modalShowContractDeliverables" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            {{ $deliverable->ContratoEntregableNombre }}
                                        </div>
                                        <div class="col-sm-4">
                                            {{ $deliverable->ContratoEntregablePlazo }}
                                        </div>
                                        <div class="col-sm-4">
                                            {{ $deliverable->ContratoEntregableStatus }}
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="row">
                                    <div class="col-sm-12">No hay registros</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="comments">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-8"><b>Comentario</b></div>
                            <div class="col-sm-4"><b>Autor</b></div>
                            <div class="col-sm-12 margin-top--5 margin-bottom--5">
                                <hr class="margin-clear">
                            </div>
                        </div>
                        <div class="items-relation">
                            @forelse($contracts['one']->comments as $comment)
                                <a href="#" data-id="{{ $comment->tbComentarioID  }}" data-author="{{ $comment->tbCTOUsuarioID_Comentario }}" data-user="{{ Auth::id() }}" data-toggle="modal" data-target="#showCommentModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <p id="commentDescription" class="help-block help-block--textarea" style="background-color: #f5f5f5; border: 1px solid #f5f5f5">{{ $comment->Comentario }}</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <small class="text-muted">
                                                <b id="commentAuthor">{{ $comment->user->person->PersonaNombreCompleto  }}</b><br>
                                                <span id="commentDate">{{  Carbon\Carbon::parse($comment->ComentarioFecha)->formatLocalized('%A %d %B %Y') }}</span>
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="row">
                                    <div class="col-sm-12">No hay registros</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    <input type="hidden" name="_recordId" value="{{ $contracts['one']->getKey() }}">
@endif
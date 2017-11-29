@if($estimates['all']->count() == 0)
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
                @include('panel.constructionwork.estimate.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($estimates['all'] as $index => $estimate)
                    @if ($estimate->tbEstimacionID == $estimates['one']->tbEstimacionID)
                        <div id="item{{ $estimate->tbEstimacionID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $estimate->EstimacionLabel }}</h4>
                            <p class="small">
                                {{ $estimate->contract->ContratoAlias}}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $estimate->tbEstimacionID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $estimate->tbEstimacionID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $estimate->EstimacionLabel }}</h4>
                        <p class="small">
                            {{ $estimate->contract->ContratoAlias}}
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
                    <li role="presentation">
                        <a href="#amounts" aria-controls="amounts" role="tab" data-toggle="tab" data-type="own">
                            Importes
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#invoices" aria-controls="invoices" role="tab" data-toggle="tab" data-type="relation">
                            Facturas
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#payments" aria-controls="payments" role="tab" data-toggle="tab" data-type="relation">
                            Pagos
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab" data-type="relation" data-element="#modalComment">
                            Comentarios
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">
                            Etiqueta
                        </label>
                        <p class="help-block">{{ $estimates['one']->EstimacionLabel }}</p>
                    </div>
                    <div class="form-group col-sm-7">
                        <label class="form-label-full">
                            Empresa contratista
                        </label>
                        <p class="help-block">{{ $estimates['one']->contract->contractor->business[0]->EmpresaAlias }}</p>
                    </div>
                    <div class="form-group col-sm-5">
                        <label class="form-label-full">
                            Importe contratado
                        </label>
                        <p class="help-block">${{ number_format(ifempty($estimates['one']->contract->ContratoImporteContratado, '0'), 2) }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-7">
                        <label class="form-label-full">
                            Contrato
                        </label>
                        <p class="help-block">{{ $estimates['one']->contract->ContratoAlias }}</p>
                    </div>
                    <div class="form-group col-sm-5">
                        <label class="form-label-full">
                            Importe anticipo
                        </label>
                        <p class="help-block">${{ number_format(ifempty($estimates['one']->contract->ContratoAnticipoMonto, '0'), 2) }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">
                            Tipo Obra
                        </label>
                        <p class="help-block">{{ ifempty($estimates['one']->EstimacionObraTipo) }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">
                            Concepto
                        </label>
                        <p class="help-block">{{ ifempty($estimates['one']->EstimacionConcepto) }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">
                            Número
                        </label>
                        <p class="help-block">{{ ifempty($estimates['one']->EstimacionNumero, '0') }}</p>
                    </div>
                    <div class="form-group col-sm-7">
                        <label class="form-label-full">
                            Fecha estimación
                        </label>
                        <p class="help-block">
                            @if ($estimates['one']->EstimacionFechaEstimacion != '0000-00-00 00:00:00' && !empty($estimates['one']->EstimacionFechaEstimacion))
                                {{ Carbon\Carbon::parse($estimates['one']->EstimacionFechaEstimacion )->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-5">
                        <label class="form-label-full">
                            Secuencia
                        </label>
                        <p class="help-block">{{ ifempty($estimates['one']->EstimacionOrdenConsecutivo, '0') }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label>
                            Es preestimación
                            @if ($estimates['one']->EstimacionPreestimacion == 1)
                                <span class="fa fa-check-square fa-fw text-info"></span>
                            @else
                                <span class="fa fa-square-o fa-fw"></span>
                            @endif
                        </label>
                        <label>
                            Es finiquito
                            @if ($estimates['one']->EstimacionFiniquito == 1)
                                <span class="fa fa-check-square fa-fw text-info"></span>
                            @else
                                <span class="fa fa-square-o fa-fw"></span>
                            @endif
                        </label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($estimates['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $estimates['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $estimates['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($estimates['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $estimates['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $estimates['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $estimates['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $estimates['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="amounts">
                    <div class="form-group col-sm-8">
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 1px"></div>
                            <div class="col-sm-4" style="padding: 0 1px">Contrato</div>
                            <div class="col-sm-4" style="padding: 0 1px">Ésta estimación</div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4" style="padding-right: 1px">
                                <label style="font-size: 13px">Anticipo amortizado (%)</label>
                            </div>
                            <div class="col-sm-4" style="padding: 0 1px">
                                <p class="help-block">{{ number_format(ifempty($estimates['one']->contract->ContratoAnticipoPCT, '0'), 2) }}</p>
                            </div>
                            <div class="col-sm-4" style="padding: 0 1px">
                                <p class="help-block">{{ number_format(ifempty($estimates['one']->EstimacionAnticipoAmortizadoPCT, '0'), 2) }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4 margin-top--10" style="padding-right: 1px">
                                <label style="font-size: 13px">Garantía retenida (%)</label>
                            </div>
                            <div class="col-sm-4 margin-top--10" style="padding: 0 1px">
                                <p class="help-block">{{ number_format(ifempty($estimates['one']->contract->ContratoFondoGarantiaPCT, '0'), 2) }}</p>
                            </div>
                            <div class="col-sm-4 margin-top--10" style="padding: 0 1px">
                                <p class="help-block">{{ number_format(ifempty($estimates['one']->EstimacionGarantiaRetenidaPCT, '0'), 2) }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4 margin-top--10" style="padding-right: 1px">
                                <label style="font-size: 13px">Otras retenciones (%)</label>
                            </div>
                            <div class="col-sm-4 margin-top--10" style="padding: 0 1px">
                                <p class="help-block">{{ number_format(ifempty($estimates['one']->contract->ContratoOtrasRetencionesPCT, '0'), 2) }}</p>
                            </div>
                            <div class="col-sm-4 margin-top--10" style="padding: 0 1px">
                                <p class="help-block">{{ number_format(ifempty($estimates['one']->EstimacionOtrasRetencionesPCT, '0'), 2) }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4 margin-top--10" style="padding-right: 1px">
                                <label style="font-size: 13px">Otras retenciones conceptos</label>
                            </div>
                            <div class="col-sm-8 margin-top--10" style="padding: 0 1px">
                                <p class="help-block">{{ ifempty($estimates['one']->EstimacionOtrasRetencionesConcepto) }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4 margin-top--10" style="padding-right: 1px">
                                <label style="font-size: 13px">Descuentos (%)</label>
                            </div>
                            <div class="col-sm-8 margin-top--10" style="padding: 0 1px">
                                <p class="help-block">{{ number_format(ifempty($estimates['one']->EstimacionDescuentoPCT, '0'), 2) }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4 margin-top--10" style="padding-right: 1px">
                                <label style="font-size: 13px">Descuentos conceptos</label>
                            </div>
                            <div class="col-sm-8 margin-top--10" style="padding: 0 1px">
                                <p class="help-block">{{ ifempty($estimates['one']->EstimacionDescuentoConcepto) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">
                                    Importe en generadores
                                </label>
                                <p class="help-block">${{ number_format(ifempty($estimates['one']->EstimacionImporteGenerado, '0'), 2) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">
                                    Importe presentado (sin IVA)
                                </label>
                                <p class="help-block">${{ number_format(ifempty($estimates['one']->EstimacionImportePresentado, '0'), 2) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">
                                    Importe estimado
                                </label>
                                <p class="help-block">${{ number_format(ifempty($estimates['one']->EstimacionImporteEstimado, '0'), 2) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">
                                    Anticipo amortizado importe
                                </label>
                                <p class="help-block">${{ number_format(ifempty($estimates['one']->EstimacionAnticipoAmortizadoMonto, '0'), 2) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">
                                    Garantía retenida importe
                                </label>
                                <p class="help-block">${{ number_format(ifempty($estimates['one']->EstimacionGarantiaRetenidaMonto, '0'), 2) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">
                                    Otras retenciones importe
                                </label>
                                <p class="help-block">${{ number_format(ifempty($estimates['one']->EstimacionOtrasRetencionesMonto, '0'), 2) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">
                                    Descuentos importe
                                </label>
                                <p class="help-block">${{ number_format(ifempty($estimates['one']->EstimacionDescuentoMonto, '0'), 2) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">
                                    Subtotal
                                </label>
                                <p class="help-block">${{ number_format(ifempty($estimates['one']->EstimacionSubtotalImporte, '0'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="invoices">

                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="payments">

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
                            @forelse($estimates['one']->comments as $comment)
                                <a href="#" data-id="{{ $comment->tbComentarioID  }}" data-toggle="modal" data-author="{{ $comment->tbCTOUsuarioID_Comentario }}" data-user="{{ Auth::id() }}" data-target="#showCommentModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
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
    <input type="hidden" name="_recordId" value="{{ $estimates['one']->getKey() }}">
@endif
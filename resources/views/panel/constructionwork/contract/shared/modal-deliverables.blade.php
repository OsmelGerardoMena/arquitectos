<div class="modal fade" id="modalSaveContractDeliverables" tabindex="-1" role="dialog" aria-labelledby="modalSaveContractDeliverables">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-right" style="position: absolute; width: 100%; top: 0; left: 0; padding: 10px">
                    <button type="button" class="saveButton btn btn-primary btn-sm">
                        <span class="fa fa-floppy-o fa-fw"></span>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <span class="fa fa-times fa-fw"></span>
                        </span>
                    </button>
                </div>
                <h4 class="modal-title margin-bottom--10">Nuevo entregable</h4>
                <form action="{{ url('ajax/action/contract/deliverables/save') }}" method="POST" class="row">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Contrato</label>
                        <input type="text"
                               value="{{ $contracts['one']->ContratoAlias  }}"
                               data-clip="true"
                               class="form-control form-control-plain input-sm" disabled>
                        <input name="cto34_contractDelivery"
                               type="hidden"
                               data-clip="true"
                               value="{{ $contracts['one']->tbContratoID  }}"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nombre del entregable</label>
                        <input name="cto34_title"
                               type="text"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="cto34_description" class="form-label-full">Descripción</label>
                        <textarea name="cto34_description"
                                  rows="3" maxlength="4000"
                                  class="form-control form-control-plain"></textarea>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Plazo</label>
                        <input name="cto34_time"
                               type="text"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="clearfix"></div>
                    <div id="cto34_saveDeliveryDateContainer" class="form-group col-sm-6">
                        <label for="cto34_deliveryDateText" class="form-label-full">Fecha entregado</label>
                        <div class="input-group input-group-sm date-field">
                            <input id="cto34_saveDeliveryDateText"
                                   name="cto34_saveDeliveryDateText"
                                   type="text"
                                   value="{{ old('cto34_deliveryDateText') }}"
                                   readonly
                                   class="form-control form-control-plain input-sm date-formated">
                            <input name="cto34_deliveryDate" type="hidden" value="{{ old('cto34_deliveryDate') }}">
                            <span class="input-group-addon" style="background-color: #fff">
                                <span class="fa fa-calendar fa-fw text-primary"></span>
                            </span>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-date-minus">
                                    <span class="fa fa-minus fa-fw"></span>
                                </button>
                                <button type="button" class="btn btn-default btn-today">Hoy</button>
                                <button type="button" class="btn btn-default btn-date-plus">
                                    <span class="fa fa-plus fa-fw"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <label class="form-label-full">Estatus</label>
                        <!--<select name="cto34_status" class="form-control">
                            <option value="">Seleccionar opción</option>
                            <option value="Entregado">Entregado</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>-->
                        <input type="text" name="cto34_status" class="form-control input-sm" value="Pendiente" readonly>
                    </div>
                    <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'deliverables']) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalShowContractDeliverables" tabindex="-1" role="dialog" aria-labelledby="modalShowContractDeliverables">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-right" style="position: absolute; width: 100%; top: 0; left: 0; padding: 10px">
                    <button type="button" class="modalSaveButton btn btn-primary btn-sm modalNavAction disabled">
                        <span class="fa fa-floppy-o fa-fw"></span>
                    </button>
                    <button type="button" class="modalUpdateButton btn btn-primary btn-sm modalNavAction disabled">
                        <span class="fa fa-pencil fa-fw"></span>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <span class="fa fa-times fa-fw"></span>
                        </span>
                    </button>
                </div>
                <h4 class="modal-title margin-bottom--10">Entregables del Contrato</h4>
                <div class="modalAlert row hidden">
                    <div class="col-sm-12 text-center margin-top--50 margin-bottom--50">
                        <span class="fa fa-spinner fa-spin fa-fw fa-2x"></span><br>
                        Cargando...
                    </div>
                </div>
                <div class="modalLoading row">
                    <div class="col-sm-12 text-center margin-top--50 margin-bottom--50">
                        <span class="fa fa-spinner fa-spin fa-fw fa-2x"></span><br>
                        Cargando...
                    </div>
                </div>
                <div class="modalContent row hidden">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Contrato</label>
                        <p class="help-block">{{ $contracts['one']->ContratoAlias  }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nombre del entregable</label>
                        <p id="deliverableTitle" class="help-block"></p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12">
                        <label for="cto34_code" class="form-label-full">Descripción</label>
                        <p id="deliverableDescription" class="help-block help-block--textarea"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Plazo</label>
                        <p id="deliverableTime" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="cto34_code" class="form-label-full">Fecha</label>
                        <p id="deliverableDate" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="cto34_code" class="form-label-full">Estatus</label>
                        <p id="deliverableStatus" class="help-block"></p>
                    </div>
                </div>
                <div class="modalForm row hidden">
                    <form action="{{ url('ajax/action/contract/deliverables/update') }}" method="post" accept-charset="utf-8">
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Contrato</label>
                            <input type="text"
                                   value="{{ $contracts['one']->ContratoAlias  }}"
                                   data-clip="true"
                                   class="form-control form-control-plain input-sm" disabled>
                            <input name="cto34_contractDelivery"
                                   type="hidden"
                                   data-clip="true"
                                   value="{{ $contracts['one']->tbContratoID  }}"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Nombre del entregable</label>
                            <input name="cto34_title"
                                   type="text"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="cto34_description" class="form-label-full">Descripción</label>
                            <textarea name="cto34_description"
                                      rows="3"
                                      class="form-control form-control-plain"></textarea>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Plazo</label>
                            <input name="cto34_time"
                                   type="text"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="clearfix"></div>
                        <div id="cto34_deliveryDateContainer" class="form-group col-sm-6">
                            <label for="cto34_deliveryDateText" class="form-label-full">Fecha entregado</label>
                            <div class="input-group input-group-sm date-field">
                                <input id="cto34_deliveryDateText"
                                       name="cto34_deliveryDateText"
                                       type="text"
                                       value="{{ old('cto34_deliveryDateText') }}"
                                       readonly="readonly"
                                       class="form-control form-control-plain input-sm date-formated">
                                <input name="cto34_deliveryDate" type="hidden" value="{{ old('cto34_deliveryDate') }}">
                                <span class="input-group-addon" style="background-color: #fff">
                                             <span class="fa fa-calendar fa-fw text-primary"></span>
                                            </span>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-date-minus">
                                        <span class="fa fa-minus fa-fw"></span>
                                    </button>
                                    <button type="button" class="btn btn-default btn-today">Hoy</button>
                                    <button type="button" class="btn btn-default btn-date-plus">
                                        <span class="fa fa-plus fa-fw"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="form-label-full">Estatus</label>
                            <!--<select name="cto34_status" class="form-control">
                                <option value="">Seleccionar opción</option>
                                <option value="Entregado">Entregado</option>
                                <option value="Pendiente">Pendiente</option>
                            </select>-->
                            <input type="text" name="cto34_status" class="form-control input-sm" value="" readonly>
                        </div>
                        <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'deliverables']) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="cto34_id" value="">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="saveBuildingModal" tabindex="-1" role="dialog" aria-labelledby="saveBuildingModal">
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
                <h4 class="modal-title margin-bottom--10">Nuevo Edificio</h4>
                <form action="{{ url('ajax/action/levels/save') }}" method="POST" class="row">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Alias</label>
                        <input name="cto34_alias"
                               type="text"
                               placeholder="Clave + Nombre"
                               disabled
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Edificio</label>
                        <select name="cto34_building" class="form-control input-sm">
                            <option value="">Seleccionar opción</option>
                            @foreach($buildings['all'] as $building)
                                <option value="{{ $building->tbUbicaEdificioID }}">{{ $building->UbicaEdificioAlias }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Clave</label>
                        <input name="cto34_code"
                               type="text"
                               value="{{ old('cto34_code') }}"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nombre</label>
                        <input name="cto34_name"
                               type="text"
                               value="{{ old('cto34_name') }}"
                               maxlength="100"
                               autocomplete="off"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Descripción</label>
                        <textarea name="cto34_description"
                                  rows="2"
                                  maxlength="4000"
                                  class="form-control form-control-plain"></textarea>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Suma a los niveles del proyecto
                            <input name="cto34_sumLevel"
                                   type="checkbox"
                                   value="1">
                        </label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Suma a las áreas del proyecto
                            <input name="cto34_sumArea"
                                   type="checkbox"
                                   value="1">
                        </label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Nivel superficie</label>
                        <input name="cto34_surfaceLevel"
                               type="text"
                               value="0"
                               class="form-control form-control-plain input-sm" readonly>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Nivel NPT</label>
                        <input name="cto34_nptLevel"
                               type="text"
                               value="{{ old('cto34_nptLevel') }}"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'levels']) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="showBuildingModal" tabindex="-1" role="dialog" aria-labelledby="showBuildingModal">
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
                <h4 class="modal-title margin-bottom--10">Edificio</h4>
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
                        <label class="form-label-full">Alias</label>
                        <p id="buildingAlias" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Clave</label>
                        <p id="buildingCode" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nombre</label>
                        <p id="buildingName" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Descripción</label>
                        <p id="buildingDescription" class="help-block help-block--textarea"></p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Área de desplante (m2)</label>
                        <p id="buildingShadeArea" class="help-block"></p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Área nominal (m2)</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="help-block">Interior: <span id="buildingTotalArea" ></span></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="help-block">Exterior: <span id="buildingTotalAreaExt"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Áreas registradas en sistema (m2)</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="help-block">Interior: <span id="buildingTotalAreaSystem" ></span></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="help-block">Exterior: <span id="buildingTotalAreaSystemExt"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Niveles totales conocidos</label>
                        <p id="buildingTotalLevels" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Niveles totales registrados</label>
                        <p id="buildingLevels" class="help-block"></p>
                    </div>
                </div>
                <div class="modalForm row hidden">
                    <form action="{{ url('ajax/buildings/actions/update') }}" method="post" accept-charset="utf-8">
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Alias</label>
                            <input name="cto34_alias"
                                   type="text"
                                   disabled
                                   placeholder="Clave + Nombre"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Clave</label>
                            <input name="cto34_code"
                                   type="text"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Nombre</label>
                            <input name="cto34_name"
                                   type="text"
                                   maxlength="100"
                                   autocomplete="off"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Descripción</label>
                            <textarea name="cto34_description"
                                      rows="2"
                                      maxlength="4000"
                                      class="form-control form-control-plain"></textarea>
                            <p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Área de desplante (m2)</label>
                            <input name="cto34_shadeArea"
                                   type="text"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Área nominal (m2)</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                        <input name="cto34_totalAreaInt" type="text" class="form-control" value="{{ old('cto34_totalAreaInt') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                        <input name="cto34_totalAreaExt" type="text" class="form-control" value="{{ old('cto34_totalAreaExt') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Áreas registradas en sistema (m2)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                <input name="cto34_totalAreaSystem" type="text" class="form-control" value="0.0" disabled>
                                <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                <input name="cto34_totalAreaSystemExt" type="text" class="form-control" value="0.0" disabled>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Niveles totales conocidos</label>
                            <input name="cto34_levelsTotal"
                                   type="text"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Niveles totales registrados</label>
                            <input name="cto34_levels"
                                   type="text"
                                   class="form-control form-control-plain input-sm" disabled>
                        </div>
                        <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'general']) }}">
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
<div class="modal fade" id="saveLevelModal" tabindex="-1" role="dialog" aria-labelledby="saveLevelModal">
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
                <h4 class="modal-title margin-bottom--10">Nuevo nivel</h4>
                <form action="{{ url('ajax/action/levels/save') }}" method="POST" class="row">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nivel alias</label>
                        <input name="cto34_alias"
                               type="text"
                               placeholder="Clave + Nombre"
                               disabled
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Edificio alias</label>
                        @if (isset($buildings['one']))
                            <input name="cto34_buildingName"
                                   type="text"
                                   value="{{ ifempty($buildings['one']->UbicaEdificioAlias, '') }}"
                                   disabled
                                   data-clip="true"
                                   class="form-control form-control-plain input-sm">
                            <input name="cto34_building"
                                   type="hidden"
                                   data-clip="true"
                                   value="{{ ifempty($buildings['one']->tbUbicaEdificioID, '') }}">
                        @endif
                    </div>
                    <div class="form-group col-sm-3">
                        <label class="form-label-full">Consecutivo</label>
                        <input name="cto34_consecutive"
                               type="number"
                               value="{{ old('cto34_consecutive') }}"
                               maxlength="10"
                               data-clip="true"
                               class="form-control form-control-plain input-sm" readonly>
                    </div>
                    <div class="form-group col-sm-3">
                        <label class="form-label-full">Clave</label>
                        <input name="cto34_code"
                               type="text"
                               value="{{ old('cto34_code') }}"
                               maxlength="10"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-6">
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
                    <div class="form-group col-sm-12">
                        <label>Suma a los niveles del proyecto
                            <input name="cto34_sumLevel"
                                   type="checkbox"
                                   value="1">
                        </label>
                        <label>Suma a las áreas del proyecto
                            <input name="cto34_sumArea"
                                   type="checkbox"
                                   value="1">
                        </label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Nivel NPT</label>
                        <input name="cto34_nptLevel"
                               type="text"
                               value="{{ old('cto34_nptLevel') }}"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Locales registrados en sistema</label>
                        <input name="cto34_locals"
                               type="text"
                               value="0"
                               class="form-control form-control-plain input-sm" disabled>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Superficies de éste nivel (m2)</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                    <input name="cto34_surfaceLevelInt" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                    <input name="cto34_surfaceLevelExt" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Superficies registradas</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                    <input type="text" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                    <input type="text" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'levels']) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="showLevelModal" tabindex="-1" role="dialog" aria-labelledby="showLevelModal">
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
                <h4 class="modal-title margin-bottom--10">Nivel</h4>
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
                        <label class="form-label-full">Nivel alias</label>
                        <p id="levelAlias" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Edificio alias</label>
                        <p id="levelBuilding" class="help-block"></p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-3">
                        <label for="cto34_code" class="form-label-full">Consecutivo</label>
                        <p id="levelConsecutive" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="cto34_code" class="form-label-full">Clave</label>
                        <p id="levelCode" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Nombre</label>
                        <p id="levelName" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="cto34_description" class="form-label-full">Descripción</label>
                        <p id="levelDescription" class="help-block help-block--textarea"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Suma a los niveles del proyecto <span id="levelSum"></span></label>
                        <label>Suma a las áreas del proyecto <span id="levelAreaSum"></span></label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label for="cto34_nptLevel" class="form-label-full">Nivel NPT</label>
                        <p id="levelNPT" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="cto34_nptLevel" class="form-label-full">Locales registrados en sistema</label>
                        <p id="levelLocals" class="help-block"></p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Superficies de éste nivel (m2)</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="help-block">Interior: <span id="levelArea"></span></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="help-block">Exterior: <span id="levelAreaExt"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Superficies registradas</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="help-block">Interior: <span id="levelAreaSystem"></span></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="help-block">Exterior: <span id="levelAreaSystemExt"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modalForm row hidden">
                    <form action="{{ url('ajax/action/levels/update') }}" method="post" accept-charset="utf-8">
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Nivel alias</label>
                            <input name="cto34_alias"
                                   type="text"
                                   value="{{ old('cto34_alias') }}"
                                   disabled
                                   placeholder="Clave + Nombre"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Edificio alias</label>
                            <input name="" type="text" value="{{ $buildings['one']->UbicaEdificioAlias }}" class="form-control input-sm" readonly>
                            <input name="cto34_building" type="hidden" value="{{ $buildings['one']->tbUbicaEdificioID }}" class="form-control input-sm">
                            <!--<select name="cto34_building" class="form-control input-sm" readonly>

                                <option value="">Seleccionar opción</option>
                                @foreach($buildings['all'] as $building)
                                    <option value="{{ $building->tbUbicaEdificioID }}">{{ $building->UbicaEdificioAlias }}</option>
                                @endforeach
                            </select>-->
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="form-label-full">Consecutivo</label>
                            <input name="cto34_consecutive"
                                   type="text"
                                   value="{{ old('cto34_consecutive') }}"
                                   maxlength="10"
                                   class="form-control form-control-plain input-sm" readonly>
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="form-label-full">Clave</label>
                            <input name="cto34_code"
                                   type="text"
                                   value="{{ old('cto34_code') }}"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Nombre</label>
                            <input name="cto34_name"
                                   value="{{ old('cto34_name') }}"
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
                        <div class="form-group col-sm-12">
                            <label>Suma a los niveles del proyecto
                                <input name="cto34_sumLevel"
                                       type="checkbox"
                                       value="1">
                            </label>
                            <label>Suma a las áreas del proyecto
                                <input name="cto34_sumArea"
                                       type="checkbox"
                                       value="1">
                            </label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Nivel NPT</label>
                            <input name="cto34_nptLevel"
                                   type="text"
                                   value="{{ old('cto34_nptLevel') }}"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Locales registrados en sistema</label>
                            <input name="cto34_locals"
                                   type="text"
                                   value=""
                                   class="form-control form-control-plain input-sm" disabled="">
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Superficies de éste nivel (m2)</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                        <input name="cto34_surfaceLevel" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                        <input name="cto34_surfaceLevelExt" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Superficies registradas</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                        <input name="cto34_surfaceLevelSystem" type="text" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                        <input name="cto34_surfaceLevelSystemExt" type="text" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'levels']) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="cto34_id" value="">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

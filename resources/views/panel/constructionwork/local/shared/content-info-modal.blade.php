{{--
    Local Modal
    Muestra los modales para locales
 --}}
<div class="modal fade" id="saveLocalModal" tabindex="-1" role="dialog" aria-labelledby="saveLocalModal">
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
                <h4 class="modal-title margin-bottom--10">Nuevo local</h4>
                <form action="{{ url('ajax/action/locals/save') }}" method="POST" class="row">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Edificio alias</label>
                    <!--<select name="cto34_building" class="form-control input-sm">
                            <option value="">Seleccionar opción</option>
                            @foreach($buildings['all'] as $building)
                        <option value="{{ $building->tbUbicaEdificioID }}">{{ $building->UbicaEdificioAlias }}</option>
                            @endforeach
                            </select>-->
                        <input name="cto34_buildingName"
                               type="text"
                               value="{{ $buildings['one']->UbicaEdificioAlias }}"
                               disabled
                               data-clip="true"
                               class="form-control form-control-plain input-sm">
                        <input name="cto34_building"
                               type="hidden"
                               data-clip="true"
                               value="{{ $buildings['one']->tbUbicaEdificioID }}">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nivel alias</label>
                        @if (isset($levels['one']))
                            <input name="" type="text" data-clip="true" class="form-control input-sm" value="{{ $levels['one']->UbicaNivelAlias }}" readonly>
                            <input name="cto34_level" type="hidden" class="form-control input-sm" data-clip="true" value="{{ $levels['one']->tbUbicaNivelID }}">
                        @else
                            <select name="cto34_level" class="form-control input-sm">
                                <option value="">Seleccionar opción</option>
                                @foreach ($buildings['one']->levels as $level)
                                    <option value="{{ $level->tbUbicaNivelID  }}">{{ $level->UbicaNivelAlias }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Local alias</label>
                        <input name="cto34_alias"
                               type="text"
                               placeholder="Clave + Nombre"
                               disabled
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Clave</label>
                        <input name="cto34_code"
                               type="text"
                               value="{{ old('cto34_code') }}"
                               maxlength="10"
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
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Tipo</label>
                        <select name="cto34_type" class="form-control input-sm">
                            <option value="">Seleccionar opción</option>
                            <option value="Interior">Interior</option>
                            <option value="Exterior">Exterior</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Área (m2)</label>
                        <input name="cto34_area"
                               type="text"
                               value="{{ old('cto34_area') }}"
                               class="form-control form-control-plain input-sm">
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6 col-sm-offset-6">
                        <label class="form-label-full">Suma a las áreas del proyecto
                            <input name="cto34_sumArea"
                                   type="checkbox"
                                   value="1">
                        </label>
                    </div>
                    <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'locals']) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="showLocalModal" tabindex="-1" role="dialog" aria-labelledby="showLocalModal">
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
                <h4 class="modal-title margin-bottom--10">Local</h4>
                <div class="modalAlert row hidden">
                    <div class="col-sm-12  margin-top--50 margin-bottom--50 text-center"></div>
                </div>
                <div class="modalLoading row">
                    <div class="col-sm-12 text-center margin-top--50 margin-bottom--50">
                        <span class="fa fa-spinner fa-spin fa-fw fa-2x"></span><br>
                        Cargando...
                    </div>
                </div>
                <div class="modalContent row hidden">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Edificio alias</label>
                        <p id="localBuilding" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nivel alias</label>
                        <p id="localLevel" class="help-block"></p>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Local alias</label>
                        <p id="localAlias" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Clave</label>
                        <p id="localCode" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nombre</label>
                        <p id="localName" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Tipo</label>
                        <p id="localType" class="help-block"></p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Área (m2)</label>
                        <p id="localArea" class="help-block"></p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6 col-sm-offset-6">
                        <label for="cto34_surfaceLevel">Suma a las áreas del proyecto <span id="localSumArea"></span></label>
                    </div>
                </div>
                <div class="modalForm row hidden">
                    <form action="{{ url('ajax/action/locals/update') }}" method="post" accept-charset="utf-8">
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Edificio alias</label>
                            <select name="cto34_building" class="form-control input-sm">
                                <option value="">Seleccionar opción</option>
                                @foreach($buildings['all'] as $building)
                                    <option value="{{ $building->tbUbicaEdificioID }}">{{ $building->UbicaEdificioAlias }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Nivel alias</label>
                            @if (isset($levels['one']))
                                <input name="" type="text" data-clip="true" class="form-control input-sm" value="{{ $levels['one']->UbicaNivelAlias }}" readonly>
                                <input name="cto34_level" type="hidden" class="form-control input-sm" data-clip="true" value="{{ $levels['one']->tbUbicaNivelID }}">
                            @else
                                <select name="cto34_level" class="form-control input-sm" disabled>
                                    <option value="">Seleccionar opción</option>
                                </select>
                            @endif
                        </div>

                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Local alias</label>
                            <input name="cto34_alias"
                                   type="text"
                                   placeholder="Clave + Nombre"
                                   disabled
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Clave</label>
                            <input name="cto34_code"
                                   type="text"
                                   value="{{ old('cto34_code') }}"
                                   maxlength="10"
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
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Tipo</label>
                            <select name="cto34_type" class="form-control input-sm">
                                <option value="">Seleccionar opción</option>
                                <option value="Interior">Interior</option>
                                <option value="Exterior">Exterior</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Área (m2)</label>
                            <input name="cto34_area"
                                   type="text"
                                   value="{{ old('cto34_area') }}"
                                   class="form-control form-control-plain input-sm">
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-6 col-sm-offset-6">
                            <label class="form-label-full">Suma a las áreas del proyecto
                                <input name="cto34_sumArea"
                                       type="checkbox"
                                       value="1">
                            </label>
                        </div>
                        <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'locals']) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="cto34_id" value="">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
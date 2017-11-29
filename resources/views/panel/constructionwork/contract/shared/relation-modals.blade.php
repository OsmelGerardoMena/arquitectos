<div class="modal fade" id="saveBusinessModal" tabindex="-1" role="dialog" aria-labelledby="saveBusinessModal">
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
                        <label class="form-label-full">Alias</label>
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
<div class="modal fade" id="showBusinessModal" tabindex="-1" role="dialog" aria-labelledby="showBusinessModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-right" style="position: absolute; width: 100%; top: 0; left: 0; padding: 10px">
                    <button type="button" class="modalSaveButton btn btn-primary btn-sm modalNavAction disabled">
                        <span class="fa fa-floppy-o fa-fw"></span>
                    </button>
                    <button type="button" class="modalUpdateButton btn btn-primary btn-sm modalNavAction disabled" disabled>
                        <span class="fa fa-pencil fa-fw"></span>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <span class="fa fa-times fa-fw"></span>
                        </span>
                    </button>
                </div>
                <h4 class="modal-title margin-bottom--10">Empresa</h4>
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
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#businessGeneral" aria-controls="businessGeneral" role="tab" data-toggle="tab">General</a>
                            </li>
                            <li role="presentation">
                                <a href="#businessConcepts" aria-controls="businessConcepts" role="tab" data-toggle="tab">Detalles</a>
                            </li>
                            <li role="presentation">
                                <a href="#businessPublic" aria-controls="businessPublic" role="tab" data-toggle="tab">Registros</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-top--5 row">
                            <div role="tabpanel" class="tab-pane active" id="businessGeneral">
                                <div class="form-group col-sm-4">
                                    <label class="form-label-full">Logotipo</label>
                                    No hay vista previa.
                                </div>
                                <div class="form-group col-sm-8">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Alias</label>
                                            <p id="businessAlias" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Razón Social</label>
                                            <p id="businessLegalName" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Nombre Comercial</label>
                                            <p id="businessComercial" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Grupo</label>
                                            <p id="businessGroup" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Alcance</label>
                                            <p id="businessScope" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="businessConcepts">
                                <div class="form-group col-sm-12">
                                    <label>Dependencia</label>
                                    <p id="businessDependency" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Especialidad</label>
                                    <p id="businessSpeciality" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Tipo de persona</label>
                                    <p id="businessType" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Slogan</label>
                                    <p id="buinessSlogan" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Página web</label>
                                    <p id="businessWeb" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Sector</label>
                                    <p id="businessSector" class="help-block"></p>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="businessPublic">
                                <div class="form-group col-sm-12">
                                    <label>RFC</label>
                                    <p id="businessRFC" class="help-block"></p>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-12">
                                    <label>Número de IMSS</label>
                                    <p id="businessIMSS" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Número de INFONAVIT</label>
                                    <p id="businessInfonavit" class="help-block"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modalForm row hidden">
                    <form action="{{ url('ajax/action/levels/update') }}" method="post" accept-charset="utf-8" class="col-sm-12">
                        <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#businessGeneralUpdate" aria-controls="businessGeneralUpdate" role="tab" data-toggle="tab">General</a>
                            </li>
                            <li role="presentation">
                                <a href="#businessConceptsUpdate" aria-controls="businessConceptsUpdate" role="tab" data-toggle="tab">Detalles</a>
                            </li>
                            <li role="presentation">
                                <a href="#businessPublicUpdate" aria-controls="businessPublicUpdate" role="tab" data-toggle="tab">Registros</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-top--5 row">
                            <div role="tabpanel" class="tab-pane active" id="businessGeneralUpdate">
                                <div class="col-sm-4">
                                    <label class="form-label-full">Logotipo</label>
                                    <!--<input type="file" name="cto34_imgLogo" id="cto34_imgLogo" class="form-control input-sm">
                                    <p class="help-block small">Subir logo (Max. 10 MB, formato: JPG o PNG)</p>-->
                                    No hay vista previa.
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="form-label-full">Alias</label>
                                            <input name="cto34_alias"
                                                   type="text"
                                                   value=""
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label-full margin-top--10">Razón social</label>
                                            <input name="cto34_legalName"
                                                   type="text"
                                                   value="{{ old('cto34_legalName') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label-full margin-top--10">Nombre comercial</label>
                                            <input name="cto34_commercialName"
                                                   type="text"
                                                   value="{{ old('cto34_commercialName') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label-full margin-top--10">Grupo</label>
                                            <select name="cto34_groups"
                                                    class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($business['groups'] as $option)
                                                    <option value="{{ $option->tbDirGrupoID }}">{{ $option->DirGrupoNombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label-full margin-top--10">Alcance</label>
                                            <input name="cto34_scope"
                                                   type="text"
                                                   value="{{ old('cto34_scope') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Cerrado <input type="checkbox" value="1" name="cto34_close"></label>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Inactivo <input type="checkbox" value="1" name="cto34_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="businessConceptsUpdate">
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Dependencia</label>
                                    <input name="cto34_dependency"
                                           type="text"
                                           value="{{ old('cto34_dependency') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Especialidad</label>
                                    <input name="cto34_especiality"
                                           type="text"
                                           value="{{ old('cto34_especiality') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Tipo de persona</label>
                                    <select name="cto34_type" class="form-control input-sm">
                                        <option value="">Seleccionar opción</option>
                                        @foreach($typePerson as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                            @if(!empty(old('cto34_type')))
                                                <option value="{{ old('cto34_type') }}" selected="selected">
                                                    {{ old('cto34_type')  }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Slogan</label>
                                    <input name="cto34_slogan"
                                           type="text"
                                           value="{{ old('cto34_slogan') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Página web</label>
                                    <input name="cto34_website"
                                           type="text"
                                           value="{{ old('cto34_website') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="form-label-full margin-top--10">Sector</label>
                                    <select name="cto34_sector" class="form-control input-sm">
                                        <option value="">Seleccionar opción</option>
                                        @foreach($sector as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                            @if(!empty(old('cto34_sector')))
                                                <option value="{{ old('cto34_sector') }}" selected="selected">
                                                    {{ old('cto34_sector')  }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="businessPublicUpdate">
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">RFC</label>
                                    <input name="cto34_legalId"
                                           maxlength="25"
                                           type="text"
                                           value="{{ old('cto34_legalId') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Número de IMSS</label>
                                    <input name="cto34_imssNum"
                                           type="text"
                                           value="{{ old('cto34_imssNum') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Número de INFONAVIT</label>
                                    <input name="cto34_infonavitNum"
                                           type="text"
                                           value="{{ old('cto34_infonavitNum') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'general']) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="cto34_id" value="">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showPersonModal" tabindex="-1" role="dialog" aria-labelledby="showPersonModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-right" style="position: absolute; width: 100%; top: 0; left: 0; padding: 10px">
                    <button type="button" class="modalSaveButton btn btn-primary btn-sm modalNavAction disabled">
                        <span class="fa fa-floppy-o fa-fw"></span>
                    </button>
                    <button type="button" class="modalUpdateButton btn btn-primary btn-sm modalNavAction disabled" disabled>
                        <span class="fa fa-pencil fa-fw"></span>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <span class="fa fa-times fa-fw"></span>
                        </span>
                    </button>
                </div>
                <h4 class="modal-title margin-bottom--10">Persona</h4>
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
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#personGeneral" aria-controls="personGeneral" role="tab" data-toggle="tab">General</a>
                            </li>
                            <li role="presentation">
                                <a href="#personConcepts" aria-controls="personConcepts" role="tab" data-toggle="tab">Conceptos</a>
                            </li>
                            <li role="presentation">
                                <a href="#personOthers" aria-controls="personOthers" role="tab" data-toggle="tab">Otros</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-top--5 row">
                            <div role="tabpanel" class="tab-pane active" id="personGeneral">
                                <div class="col-sm-4">
                                    <label class="form-label-full">Foto</label>
                                    No hay vista previa.
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label>Nombre por apellidos</label>
                                            <p id="personAlias" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Nombre directo</label>
                                            <p id="personDirectName" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Nombre completo</label>
                                            <p id="personFullName" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Categoría</label>
                                            <p id="personCategory" class="help-block"></p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Cargo en la obra</label>
                                            <p id="personWorkJob" class="help-block"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="personConcepts">
                                <div class="form-group col-sm-6">
                                    <label>Genero</label>
                                    <p id="personGender" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Prefijo</label>
                                    <p id="personPrefix" class="help-block"></p>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-6">
                                    <label>Nombre</label>
                                    <p id="personName" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Apellido Paterno</label>
                                    <p id="personLastName1" class="help-block"></p>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-6">
                                    <label>Apellido Materno</label>
                                    <p id="personLastName2" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Saludo</label>
                                    <p id="personHello" class="help-block"></p>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-6">
                                    <label>Fecha de nacimiento</label>
                                    <p id="personBirthday" class="help-block"></p>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-6">
                                    <label>Tipo de identificación</label>
                                    <p id="personCardID" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Número de identificación</label>
                                    <p id="personCardNum" class="help-block"></p>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="personOthers">
                                <div class="form-group col-sm-12">
                                    <label>Contacto de emergencia</label>
                                    <p id="personEmergency" class="help-block"></p>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Fecha de alta</label>
                                    <p id="personRegisteredAt" class="help-block"></p>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-6">
                                    <label>Fecha de baja</label>
                                    <p id="personDeletedAt" class="help-block"></p>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="comments">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modalForm row hidden">
                    <form action="{{ url('ajax/action/levels/update') }}" method="post" accept-charset="utf-8" class="col-sm-12">
                        <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#personGeneralUpdate" aria-controls="personGeneralUpdate" role="tab" data-toggle="tab">General</a>
                            </li>
                            <li role="presentation">
                                <a href="#personConceptsUpdate" aria-controls="personConceptsUpdate" role="tab" data-toggle="tab">Detalles</a>
                            </li>
                            <li role="presentation">
                                <a href="#personPublicUpdate" aria-controls="personPublicUpdate" role="tab" data-toggle="tab">Registros</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-top--5 row">
                            <div role="tabpanel" class="tab-pane active" id="personGeneralUpdate">
                                <div class="col-sm-4">
                                    <label class="form-label-full">Logotipo</label>
                                    <!--<input type="file" name="cto34_imgLogo" id="cto34_imgLogo" class="form-control input-sm">
                                    <p class="help-block small">Subir logo (Max. 10 MB, formato: JPG o PNG)</p>-->
                                    No hay vista previa.
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="form-label-full">Alias</label>
                                            <input name="cto34_alias"
                                                   type="text"
                                                   value=""
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label-full margin-top--10">Razón social</label>
                                            <input name="cto34_legalName"
                                                   type="text"
                                                   value="{{ old('cto34_legalName') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label-full margin-top--10">Nombre comercial</label>
                                            <input name="cto34_commercialName"
                                                   type="text"
                                                   value="{{ old('cto34_commercialName') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label-full margin-top--10">Grupo</label>
                                            <select name="cto34_groups"
                                                    class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($business['groups'] as $option)
                                                    <option value="{{ $option->tbDirGrupoID }}">{{ $option->DirGrupoNombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label-full margin-top--10">Alcance</label>
                                            <input name="cto34_scope"
                                                   type="text"
                                                   value="{{ old('cto34_scope') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Cerrado <input type="checkbox" value="1" name="cto34_close"></label>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Inactivo <input type="checkbox" value="1" name="cto34_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="personConceptsUpdate">
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Dependencia</label>
                                    <input name="cto34_dependency"
                                           type="text"
                                           value="{{ old('cto34_dependency') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Especialidad</label>
                                    <input name="cto34_especiality"
                                           type="text"
                                           value="{{ old('cto34_especiality') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Tipo de persona</label>
                                    <select name="cto34_type" class="form-control input-sm">
                                        <option value="">Seleccionar opción</option>
                                        @foreach($typePerson as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                            @if(!empty(old('cto34_type')))
                                                <option value="{{ old('cto34_type') }}" selected="selected">
                                                    {{ old('cto34_type')  }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Slogan</label>
                                    <input name="cto34_slogan"
                                           type="text"
                                           value="{{ old('cto34_slogan') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Página web</label>
                                    <input name="cto34_website"
                                           type="text"
                                           value="{{ old('cto34_website') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="form-label-full margin-top--10">Sector</label>
                                    <select name="cto34_sector" class="form-control input-sm">
                                        <option value="">Seleccionar opción</option>
                                        @foreach($sector as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                            @if(!empty(old('cto34_sector')))
                                                <option value="{{ old('cto34_sector') }}" selected="selected">
                                                    {{ old('cto34_sector')  }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="personPublicUpdate">
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">RFC</label>
                                    <input name="cto34_legalId"
                                           maxlength="25"
                                           type="text"
                                           value="{{ old('cto34_legalId') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Número de IMSS</label>
                                    <input name="cto34_imssNum"
                                           type="text"
                                           value="{{ old('cto34_imssNum') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-full margin-top--10">Número de INFONAVIT</label>
                                    <input name="cto34_infonavitNum"
                                           type="text"
                                           value="{{ old('cto34_infonavitNum') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'signatures']) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="cto34_id" value="">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
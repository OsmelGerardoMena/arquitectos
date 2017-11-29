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
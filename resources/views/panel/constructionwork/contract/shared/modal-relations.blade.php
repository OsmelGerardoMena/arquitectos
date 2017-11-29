{{--
    Modals
    Se registran los modals para hacer acciones sin dejar el registro actual

    <modals>
--}}

{{--
    Modal buscar o agregar empresa
--}}
<div class="modal fade" id="modalBusinessInfo" tabindex="-1" role="dialog" aria-labelledby="modalBusinessInfo">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="position: relative">
                <div class="text-right" style="position: absolute; width: 100%; top: 0; left: 0; padding: 10px">
                    <button id="saveEditBusiness" type="button" class="btn btn-primary btn-sm disabled">
                        <span class="fa fa-floppy-o fa-fw"></span>
                    </button>
                    <button id="enableEditBusiness" type="button" class="btn btn-primary btn-sm">
                        <span class="fa fa-pencil fa-fw"></span>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <span class="fa fa-times fa-fw"></span>
                        </span>
                    </button>
                </div>
                <h4 class="modal-title margin-bottom--10">Empresa</h4>
                <form id="saveFormBusinessInfo" action="{{ url('') }}" method="post" accept-charset="utf-8" class="row margin-top--20">
                    <div class="form-group col-sm-4">
                        <label for="cto34_imgLogo" class="form-label-full">Logotipo</label>
                        <!--                                     <img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto"> -->
                        <input type="file" name="cto34_imgLogo" id="cto34_imgLogo" class="form-control input-sm">
                        <p class="help-block small">Subir logo (Max. 10 MB, formato: JPG o PNG)</p>
                    </div>
                    <div class="form-group col-sm-8">
                        <label for="cto34_businessAlias" class="form-label-full">Empresa alias</label>
                        <input id="cto34_businessAlias"
                               name="cto34_businessAlias"
                               type="text"
                               value="{{ old('cto34_alias') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                        <label for="cto34_businessLegalName" class="form-label-full margin-top--10">Razón social</label>
                        <input id="cto34_businessLegalName"
                               name="cto34_businessLegalName"
                               type="text"
                               value="{{ old('cto34_businessLegalName') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                        <label for="cto34_commercialName" class="form-label-full margin-top--10">Nombre comercial</label>
                        <input id="cto34_commercialName"
                               name="cto34_commercialName"
                               type="text"
                               value="{{ old('cto34_commercialName') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_dependency" class="form-label-full">Dependencia</label>
                        <input id="cto34_dependency"
                               name="cto34_dependency"
                               type="text"
                               value="{{ old('cto34_dependency') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_especiality" class="form-label-full">Especialidad</label>
                        <input id="cto34_especiality"
                               name="cto34_especiality"
                               type="text"
                               value="{{ old('cto34_especiality') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_type" class="form-label-full">Tipo de persona</label>
                        <select name="cto34_type" id="cto34_type" readonly="readonly" class="form-control input-sm form-control-ghost">
                            <option value="">Seleccionar opción</option>
                            {{-- @foreach($typePerson as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                @if(!empty(old('cto34_type')))
                                    <option value="{{ old('cto34_type') }}" selected="selected">
                                        {{ old('cto34_type')  }}
                                    </option>
                                @endif
                            @endforeach--}}
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_slogan" class="form-label-full">Slogan</label>
                        <input id="cto34_slogan"
                               name="cto34_slogan"
                               type="text"
                               value="{{ old('cto34_slogan') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_website" class="form-label-full">Página web</label>
                        <input id="cto34_website"
                               name="cto34_website"
                               type="text"
                               value="{{ old('cto34_website') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_legalId" class="form-label-full">RFC</label>
                        <input id="cto34_legalId"
                               maxlength="25"
                               name="cto34_legalId"
                               type="text"
                               value="{{ old('cto34_legalId') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_imssNum" class="form-label-full">Número de IMSS</label>
                        <input id="cto34_imssNum"
                               name="cto34_imssNum"
                               type="text"
                               value="{{ old('cto34_imssNum') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_infonavitNum" class="form-label-full">Número de INFONAVIT</label>
                        <input id="cto34_infonavitNum"
                               name="cto34_infonavitNum"
                               type="text"
                               value="{{ old('cto34_infonavitNum') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_sector" class="form-label-full">Sector</label>
                        <select name="cto34_sector" id="cto34_sector" readonly="readonly"
                                class="form-control input-sm form-control-ghost">
                            <option value="">Seleccionar opción</option>
                            {{--@foreach($sector as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach--}}
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_groups" class="form-label-full">Grupo</label>

                        <select name="cto34_groups" id="cto34_groups" readonly="readonly"
                                class="form-control input-sm form-control-ghost">
                            <option value="">Seleccionar opción</option>
                            {{--@foreach($business['groups'] as $option)
                                <option value="{{ $option->tbDirGrupoID }}">{{ $option->DirGrupoNombre }}</option>
                            @endforeach--}}
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_scope" class="form-label-full">Alcance</label>
                        <input id="cto34_scope"
                               name="cto34_scope"
                               type="text"
                               value="{{ old('cto34_scope') }}"
                               readonly="readonly"
                               class="form-control form-control-plain input-sm form-control-ghost">
                    </div>
                    <div class="form-group col-sm-3">
                        Cerrado
                        <div>
                            <input type="checkbox" value="1" name="cto34_close">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        Inactivo
                        <div>
                            <input type="checkbox" value="1" name="cto34_status">
                        </div>
                    </div>
                    <input type="hidden" name="cto34_businessId">
                    <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        </div>
    </div>
</div>
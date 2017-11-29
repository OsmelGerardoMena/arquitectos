{{--
    Modals
    Se registran los modals para hacer acciones sin dejar el registro actual

    <modals>
--}}

{{--
    Modal buscar o agregar cliente directo
--}}
<div class="modal fade" id="modalBusiness" tabindex="-1" role="dialog" aria-labelledby="modalBusiness">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <span class="fa fa-window-close fa-fw"></span>
                    </span> Cerrar
                </button>
                <h4 class="modal-title margin-bottom--10">Cliente directo</h4>
                <form id="saveSearchedBusinessForm" action="{{ url('/') }}/ajax/action/save/businessWork" method="post" accept-charset="utf-8" class="row">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Empresa </label>
                        <div id="searchBusiness" class="dropdown search-ajax">
                            <input name="cto34_name"
                                   type="text"
                                   value="{{ old('cto34_directCustomerName') }}"
                                   placeholder="Ingresar nombre de empresa"
                                   autocomplete="off"
                                   class="form-control search-field input-sm">
                            <input name="cto34_search" type="hidden" value="{{ old('cto34_directCustomer') }}" class="search-hidden">
                            <ul class="dropdown-menu" aria-labelledby="dLabel">
                                <li>
                                    <a href="#" id="newBusiness" class="search-new">Agregar empresa</a>
                                </li>
                                <li class="dropdown-header search-message"></li>
                                <div class="search-result"></div>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full label-cover">Grupo
                            <select name="cto34_group" class="form-control">
                                <option value="">Seleccionar grupo</option>
                                @foreach($groups['all'] as $group)
                                    <option value="{{ $group->tbDirGrupoID  }}">{{ $group->DirGrupoNombre }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full label-cover">Alcance en obra
                            <input name="cto34_scope"
                                   type="text"
                                   value="{{ old('cto34_scope') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="hidden" name="_element" value="">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                        <input type="hidden" name="_from" value="search">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-action btn-large">
                            <span class="fa fa-floppy-o fa-fw"></span> Agregar
                        </button>
                    </div>
                </form>
                <form id="saveBusinessForm" action="{{ url('/') }}/ajax/action/save/businessWork" method="POST" accept-charset="utf-8" class="margin-top--10 row" style="display: none">
                    <div class="form-group col-sm-12">
                        <a href="#" id="returnBusiness" type="button" class="text-danger">
                            <span class="fa fa-chevron-left fa-fw"></span> Cancelar
                        </a>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Nombre corto (alias)
                            <input name="cto34_alias"
                                   type="text"
                                   value="{{ old('cto34_alias') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Razón social
                            <input name="cto34_legalName"
                                   type="text"
                                   value="{{ old('cto34_legalName') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Nombre comercial
                            <input name="cto34_commercialName"
                                   type="text"
                                   value="{{ old('cto34_commercialName') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Dependencia
                            <input name="cto34_dependency"
                                   type="text"
                                   value="{{ old('cto34_dependency') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Especialidad
                            <input name="cto34_especiality"
                                   type="text"
                                   value="{{ old('cto34_especiality') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Tipo de persona
                            <input name="cto34_type"
                                   type="text"
                                   value="{{ old('cto34_type') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Slogan
                            <input name="cto34_slogan"
                                   type="text"
                                   value="{{ old('cto34_slogan') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Página web
                            <input name="cto34_website"
                                   type="text"
                                   value="{{ old('cto34_website') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">RFC
                            <input name="cto34_legalId"
                                   maxlength="25"                                   
                                   type="text"
                                   value="{{ old('cto34_legalId') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Número de IMSS
                            <input name="cto34_imssNum"
                                   type="text"
                                   value="{{ old('cto34_imssNum') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Número de INFONAVIT
                            <input name="cto34_infonavitNum"
                                   type="text"
                                   value="{{ old('cto34_infonavitNum') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full label-cover">Sector
                            <input name="cto34_sector"
                                   type="text"
                                   value="{{ old('cto34_sector') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full label-cover">Comentarios
                            <textarea maxlength="4000" name="cto34_comments" cols="30" rows="3" class="form-control"></textarea>
                        </label>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full label-cover">Grupo
                            <select name="cto34_group" class="form-control">
                                <option value="">Seleccionar grupo</option>
                                @foreach($groups['all'] as $group)
                                    <option value="{{ $group->tbDirGrupoID  }}">{{ $group->DirGrupoNombre }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full label-cover">Alcance en obra
                            <input name="cto34_scope"
                                   type="text"
                                   value="{{ old('cto34_scope') }}"
                                   class="form-control form-control-plain input-sm">
                        </label>
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="hidden" name="_element" value="">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                        <input type="hidden" name="_from" value="save">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-action btn-large">
                            <span class="fa fa-floppy-o fa-fw"></span> Agregar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalDirectCustomer" tabindex="-1" role="dialog" aria-labelledby="modalDirectCustomer">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Cliente directo</h4>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#searchDirectCustomerTab" aria-controls="searchDirectCustomerTab" role="tab" data-toggle="tab">Buscar</a>
                    </li>
                    <li role="presentation">
                        <a href="#addDirectCustomerTab" aria-controls="addDirectCustomerTab" role="tab" data-toggle="tab">Nuevo</a>
                    </li>
                </ul>
                <div class="tab-content row margin-top--10">
                    <div role="tabpanel" class="tab-pane active col-sm-12" id="searchDirectCustomerTab">
                        <form id="saveSearchedDirectCustomerForm" action="{{ url('/') }}/ajax/action/save/businessWork" method="post" accept-charset="utf-8">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Empresa </label>
                                <select name="cto34_search"
                                        data-live-search="true"
                                        data-width="100%"
                                        class="selectpicker with-ajax margin-bottom--10 search-business">
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full label-cover">Grupo
                                    <select name="cto34_group" class="form-control">
                                        <option value="">Seleccionar grupo</option>
                                        @foreach($groups['all'] as $group)
                                            <option value="{{ $group->tbDirGrupoID  }}">{{ $group->DirGrupoNombre }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full label-cover">Alcance en obra
                                    <input name="cto34_scope"
                                           type="text"
                                           value="{{ old('cto34_scope') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-12">
                                <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                <input type="hidden" name="_from" value="search">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-action btn-large">
                                    <span class="fa fa-floppy-o fa-fw"></span> Agregar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane col-sm-12" id="addDirectCustomerTab">
                        <form id="saveDirectCustomerForm" action="{{ url('/') }}/ajax/action/save/businessWork" method="POST" accept-charset="utf-8" class="margin-top--10">
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Nombre corto (alias)
                                    <input name="cto34_alias"
                                           type="text"
                                           value="{{ old('cto34_alias') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Razón social
                                    <input name="cto34_legalName"
                                           type="text"
                                           value="{{ old('cto34_legalName') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Nombre comercial
                                    <input name="cto34_commercialName"
                                           type="text"
                                           value="{{ old('cto34_commercialName') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Dependencia
                                    <input name="cto34_dependency"
                                           type="text"
                                           value="{{ old('cto34_dependency') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Especialidad
                                    <input name="cto34_especiality"
                                           type="text"
                                           value="{{ old('cto34_especiality') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Tipo de persona
                                    <input name="cto34_type"
                                           type="text"
                                           value="{{ old('cto34_type') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Slogan
                                    <input name="cto34_slogan"
                                           type="text"
                                           value="{{ old('cto34_slogan') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Página web
                                    <input name="cto34_website"
                                           type="text"
                                           value="{{ old('cto34_website') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">RFC
                                    <input name="cto34_legalId"
                                           maxlength="25"
                                           type="text"
                                           value="{{ old('cto34_legalId') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Número de IMSS
                                    <input name="cto34_imssNum"
                                           type="text"
                                           value="{{ old('cto34_imssNum') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Número de INFONAVIT
                                    <input name="cto34_infonavitNum"
                                           type="text"
                                           value="{{ old('cto34_infonavitNum') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Sector
                                    <input name="cto34_sector"
                                           type="text"
                                           value="{{ old('cto34_sector') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full label-cover">Comentarios
                                    <textarea name="cto34_comments" maxlength="4000" cols="30" rows="3" class="form-control"></textarea>
                                </label>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full label-cover">Grupo
                                    <select name="cto34_group" class="form-control">
                                        <option value="">Seleccionar grupo</option>
                                        @foreach($groups['all'] as $group)
                                            <option value="{{ $group->tbDirGrupoID  }}">{{ $group->DirGrupoNombre }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full label-cover">Alcance en obra
                                    <input name="cto34_scope"
                                           type="text"
                                           value="{{ old('cto34_scope') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-12">
                                <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                <input type="hidden" name="_from" value="save">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-action btn-large">
                                    <span class="fa fa-floppy-o fa-fw"></span> Agregar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--
    Modal buscar o agregar cliente contratista

 --}}
<div class="modal fade" id="modalContractCustomer" tabindex="-1" role="dialog" aria-labelledby="modalContractCustomer">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Cliente contratante</h4>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#searchContractCustomerTab" aria-controls="searchContractCustomerTab" role="tab" data-toggle="tab">Buscar</a>
                    </li>
                    <li role="presentation">
                        <a href="#addContractCustomerTab" aria-controls="addContractCustomerTab" role="tab" data-toggle="tab">Nuevo</a>
                    </li>
                </ul>
                <div class="tab-content row margin-top--10">
                    <div role="tabpanel" class="tab-pane active col-sm-12" id="searchContractCustomerTab">
                        <form id="saveSearchedContractCustomerForm" action="{{ url('/') }}/ajax/action/save/businessWork" method="post" accept-charset="utf-8">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Empresa </label>
                                <select name="cto34_search"
                                        data-live-search="true"
                                        data-width="100%"
                                        class="selectpicker with-ajax margin-bottom--10 search-business">
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full label-cover">Grupo
                                    <select name="cto34_group" class="form-control">
                                        <option value="">Seleccionar grupo</option>
                                        @foreach($groups['all'] as $group)
                                            <option value="{{ $group->tbDirGrupoID  }}">{{ $group->DirGrupoNombre }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full label-cover">Alcance en obra
                                    <input name="cto34_scope"
                                           type="text"
                                           value="{{ old('cto34_scope') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-12">
                                <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                <input type="hidden" name="_from" value="search">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-action btn-large">
                                    <span class="fa fa-floppy-o fa-fw"></span> Agregar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane col-sm-12" id="addContractCustomerTab">
                        <form id="saveContractCustomerForm" action="{{ url('/') }}/ajax/action/save/businessWork" method="POST" accept-charset="utf-8" class="margin-top--10">
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Nombre corto (alias)
                                    <input name="cto34_alias"
                                           type="text"
                                           value="{{ old('cto34_alias') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Razón social
                                    <input name="cto34_legalName"
                                           type="text"
                                           value="{{ old('cto34_legalName') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Nombre comercial
                                    <input name="cto34_commercialName"
                                           type="text"
                                           value="{{ old('cto34_commercialName') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Dependencia
                                    <input name="cto34_dependency"
                                           type="text"
                                           value="{{ old('cto34_dependency') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Especialidad
                                    <input name="cto34_especiality"
                                           type="text"
                                           value="{{ old('cto34_especiality') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Tipo de persona
                                    <input name="cto34_type"
                                           type="text"
                                           value="{{ old('cto34_type') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Slogan
                                    <input name="cto34_slogan"
                                           type="text"
                                           value="{{ old('cto34_slogan') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Página web
                                    <input name="cto34_website"
                                           type="text"
                                           value="{{ old('cto34_website') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">RFC
                                    <input name="cto34_legalId"
                                           maxlength="25"
                                           type="text"
                                           value="{{ old('cto34_legalId') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Número de IMSS
                                    <input name="cto34_imssNum"
                                           type="text"
                                           value="{{ old('cto34_imssNum') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Número de INFONAVIT
                                    <input name="cto34_infonavitNum"
                                           type="text"
                                           value="{{ old('cto34_infonavitNum') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="form-label-full label-cover">Sector
                                    <input name="cto34_sector"
                                           type="text"
                                           value="{{ old('cto34_sector') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full label-cover">Comentarios
                                    <textarea name="cto34_comments" maxlength="4000" cols="30" rows="3" class="form-control"></textarea>
                                </label>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full label-cover">Grupo
                                    <select name="cto34_group" class="form-control">
                                        <option value="">Seleccionar grupo</option>
                                        @foreach($groups['all'] as $group)
                                            <option value="{{ $group->tbDirGrupoID  }}">{{ $group->DirGrupoNombre }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full label-cover">Alcance en obra
                                    <input name="cto34_scope"
                                           type="text"
                                           value="{{ old('cto34_scope') }}"
                                           class="form-control form-control-plain input-sm">
                                </label>
                            </div>
                            <div class="form-group col-sm-12">
                                <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                <input type="hidden" name="_from" value="save">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-action btn-large">
                                    <span class="fa fa-floppy-o fa-fw"></span> Agregar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--
    Modal agregar partida

 --}}
<div class="modal fade" id="modalDeparture" tabindex="-1" role="dialog" aria-labelledby="modalDeparture">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Partida</h4>
                <form id="saveDepartureForm" action="{{ url('ajax/action/save/departureWork') }}" method="post" accept-charset="utf-8" class="row">
                    <div class="form-group col-sm-4">
                        <label for="cto34_code">Clave</label>
                        <input id="cto34_code"
                               name="cto34_code"
                               type="text"
                               class="form-control input-sm">
                    </div>
                    <div class="form-group col-sm-8">
                        <label for="cto34_name">Nombre</label>
                        <input id="cto34_name"
                               name="cto34_name"
                               type="text"
                               class="form-control input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-action btn-large">
                            <span class="fa fa-floppy-o fa-fw"></span> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--
    Modal agregar subpartida
 --}}
<div class="modal fade" id="modalSubdeparture" tabindex="-1" role="dialog" aria-labelledby="modalSubdeparture">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Subpartida</h4>
                <form id="saveSubdepartureForm" action="{{ url('ajax/action/save/subdepartureWork') }}" method="post" accept-charset="utf-8" class="row">
                    <div class="form-group col-sm-4">
                        <label for="cto34_subdeparture_departure" class="form-label-full">Partida</label>
                        <select name="cto34_subdeparture_departure" id="cto34_subdeparture_departure" class="form-control input-sm">
                            <option value="" class="option-default">Seleccionar opción</option>
                            @foreach($departures['all'] as $departure)
                                <option value="{{ $departure->tbPartidaID  }}">
                                    {{ $departure->PartidaNombre  }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_code">Clave</label>
                        <input id="cto34_code"
                               name="cto34_code"
                               type="text"
                               class="form-control input-sm">
                    </div>
                    <div class="form-group col-sm-8">
                        <label for="cto34_name">Nombre</label>
                        <input id="cto34_name"
                               name="cto34_name"
                               type="text"
                               class="form-control input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-action btn-large">
                            <span class="fa fa-floppy-o fa-fw"></span> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
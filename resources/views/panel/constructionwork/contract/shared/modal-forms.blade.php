{{--
Modals
Se registran los modals para hacer acciones sin dejar el registro actual

<modals>
    --}}

    {{--
    Modal buscar o agregar empresa
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
                            <label for="searchBusiness" class="form-label-full">Empresa</label>
                            <select id="searchBusiness"
                                    name="cto34_search"
                                    data-live-search="true"
                                    data-width="100%"
                                    data-style="btn-sm btn-default"
                                    class="selectpicker with-ajax">
                            </select>
                            <input type="hidden" id="searchBusinessName" name="cto34_businessName">
                        </div>
                        <div class="form-group col-sm-6" style="display:none">
                            <label class="form-label-full label-cover">Grupo
                                <select name="cto34_group" class="form-control">
                                    <option value="">Seleccionar grupo</option>
                                    @foreach($groups['all'] as $group)
                                    <option value="{{ $group->tbDirGrupoID  }}">{{ $group->DirGrupoNombre }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-sm-6" style="display:none">
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
                                <select name="cto34_type" class="form-control">
                                    <option value="">Seleccionar Tipo</option>
                                    <option value="Física">Física</option>
                                    <option value="Moral">Moral</option>
                                </select>
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
                                <select name="cto34_sector" class="form-control">
                                    <option value="">Seleccionar Sector</option>
                                    <option value="Público">Público</option>
                                    <option value="Privado">Privado</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full label-cover">Comentarios
                                <textarea name="cto34_comments" maxlength="4000" cols="30" rows="3" class="form-control"></textarea>
                            </label>
                        </div>
                        <div class="form-group col-sm-6" style="display:none">
                            <label class="form-label-full label-cover">Grupo
                                <select name="cto34_group" class="form-control">
                                    <option value="">Seleccionar grupo</option>
                                    @foreach($groups['all'] as $group)
                                    <option value="{{ $group->tbDirGrupoID  }}">{{ $group->DirGrupoNombre }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-sm-6" style="display:none">
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
    {{--

    Modal Person
    Busca o agrega una nueva persona a la obra

    --}}
    <div class="modal fade" id="modalPerson" tabindex="-1" role="dialog" aria-labelledby="modalPerson">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title margin-bottom--10"></h4>
                    <form id="saveSearchedPersonForm" action="{{ url('/') }}/ajax/action/save/personsWork" method="post" accept-charset="utf-8" class="row">
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Persona</label>
                            <select id="cto34_searchPerson"
                                    name="cto34_searchPerson"
                                    data-live-search="true"
                                    data-width="100%"
                                    data-style="btn-sm btn-default"
                                    class="selectpicker with-ajax">

                            </select>
                            <input type="hidden" id="cto34_searchPersonName" name="cto34_searchPersonName">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Empresa</label>
                            <select id="cto34_searchBusiness"
                                    name="cto34_searchBusiness"
                                    data-live-search="true"
                                    data-width="100%"
                                    data-style="btn-sm btn-default"
                                    class="selectpicker with-ajax">

                            </select>
                            <input type="hidden" id="cto34_searchBusinessName" name="cto34_searchBusinessName">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full label-cover">Departemento
                                <input name="cto34_department"
                                       type="text"
                                       class="form-control">
                            </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full label-cover">Cargo en la obra
                                <input name="cto34_job"
                                       type="text"
                                       class="form-control">
                            </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full label-cover">Categoría
                                <select name="cto34_category" class="form-control">
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($business['categories'] as $categories)
                                    <option value="{{ $categories->tbEmpresaCategoriaID  }}">{{ $categories->EmpresaCategoriaNombre }}</option>
                                    @endforeach
                                </select>
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
                    <form id="savePersonForm" action="{{ url('/') }}/ajax/action/save/personsWork" method="POST" accept-charset="utf-8" class="margin-top--10 row" style="display: none">
                        <div class="form-group col-sm-12">
                            <a href="#" id="returnPerson" type="button" class="text-danger">
                                <span class="fa fa-chevron-left fa-fw"></span> Cancelar
                            </a>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full label-cover">Género
                                <select name="cto34_gender" class="form-control input-sm">
                                    <option value="0">Seleccionar genero</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full label-cover">Prefijo
                                <input name="cto34_personPrefix"
                                       type="text"
                                       value="{{ old('cto34_personPrefix') }}"
                                       list="personPrefix_customerSignature"
                                       class="form-control form-control-plain input-sm">
                            </label>
                            <datalist id="personPrefix_customerSignature">
                                <option value="Sr.">
                                <option value="Sra.">
                                <option value="Lic.">
                                <option value="Arq.">
                                <option value="Ing.">
                                <option value="Dr.">
                            </datalist>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full">Fecha de nacimiento</label>
                            <div class="input-group input-group-sm date-field margin-bottom--5">
                                <input name="cto34_birthdate"
                                       type="text"
                                       value="{{ old('cto34_birthdate') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain">
                                <span class="input-group-addon" style="background-color: #fff">
                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full label-cover">Nombre(s)
                                <input name="cto34_name"
                                       type="text"
                                       value="{{ old('cto34_name') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain input-sm">
                            </label>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full label-cover">Apellido Paterno
                                <input name="cto34_lastName"
                                       type="text"
                                       value="{{ old('cto34_lastName') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain input-sm">
                            </label>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full label-cover">Apellido Materno
                                <input name="cto34_lastName2"
                                       type="text"
                                       value="{{ old('cto34_lastName2') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain input-sm">
                            </label>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full label-cover">Identificación Tipo
                                <input name="cto34_idType"
                                       type="text"
                                       value="{{ old('cto34_idType') }}"
                                       list="idType_customerSignature"
                                       class="form-control form-control-plain input-sm">
                            </label>
                            <datalist id="idType_customerSignature">
                                <option value="IFE">
                                <option value="Pasaporte">
                                <option value="Licencia">
                            </datalist>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full label-cover">Identificación número
                                <input name="cto34_idNumber"
                                       type="text"
                                       value="{{ old('cto34_idNumber') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain input-sm">
                            </label>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="form-label-full">Fecha de alta</label>
                            <div class="input-group input-group-sm date-field">
                                <input name="cto34_registrationDate"
                                       type="text"
                                       value="{{ old('cto34_registrationDate') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain input-sm">
                                <span class="input-group-addon" style="background-color: #fff">
                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full label-cover">Nombre por apellidos
                                <input name="cto34_nameByLast"
                                       type="text"
                                       value="{{ old('cto34_nameByLast') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain input-sm">
                            </label>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full label-cover">Nombre directo
                                <input name="cto34_directName"
                                       type="text"
                                       value="{{ old('cto34_directName') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain input-sm">
                            </label>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full label-cover">Nombre completo
                                <input name="cto34_fullName"
                                       type="text"
                                       value="{{ old('cto34_fullName') }}"
                                       autocomplete="off"
                                       class="form-control form-control-plain input-sm">
                            </label>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full label-cover">Contacto emergencia
                                <textarea name="cto34_contactEmergency" maxlength="4000" cols="30" rows="3" class="form-control"></textarea>
                            </label>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full label-cover">Comentarios
                                <textarea name="cto34_comments" maxlength="4000" cols="30" rows="3" class="form-control"></textarea>
                            </label>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Empresa</label>
                            <select id="cto34_person_searchBusiness"
                                    name="cto34_searchBusiness"
                                    data-live-search="true"
                                    data-width="100%"
                                    data-style="btn-sm btn-default"
                                    class="selectpicker with-ajax">

                            </select>
                            <input type="hidden" id="cto34_person_searchBusinessName" name="cto34_person_searchBusinessName">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full label-cover">Departemento
                                <input name="cto34_department"
                                       type="text"
                                       class="form-control">
                            </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full label-cover">Cargo en la obra
                                <input name="cto34_job"
                                       type="text"
                                       class="form-control">
                            </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full label-cover">Categoría
                                <select name="cto34_category" class="form-control">
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($business['categories'] as $categories)
                                    <option value="{{ $categories->tbEmpresaCategoriaID  }}">{{ $categories->EmpresaCategoriaNombre }}</option>
                                    @endforeach
                                </select>
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
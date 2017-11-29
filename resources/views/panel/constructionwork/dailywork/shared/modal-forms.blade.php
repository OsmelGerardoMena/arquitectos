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
                            <small class="form-count small text-muted margin-clear"><span class="form-counter">0</span>/4000</small>
                        </label>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full label-cover">Comentarios
                            <textarea name="cto34_comments" maxlength="4000" cols="30" rows="3" class="form-control"></textarea>
                            <small class="form-count small text-muted margin-clear"><span class="form-counter">0</span>/4000</small>
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
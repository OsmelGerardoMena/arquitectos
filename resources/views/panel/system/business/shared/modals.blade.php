{{--
    Modal - #modalNewRecord
    Formulario para agregar nuevo registro

    <modal>
--}}
<div class="modal fade" id="modalNewRecord" tabindex="-1" role="dialog" aria-labelledby="modalNewRecord">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Nueva empresa</h4>
                <form id="saveForm" action="{{ $navigation['base'] }}/action/save" method="POST" accept-charset="utf-8" class="margin-top--10">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group col-sm-4">
                                <img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto">
                                <input type="file" name="cto34_imgLogo" id="" class="form-control input-sm">
                                <p class="help-block small">Subir logo (Max. 10 MB, formato: JPG o PNG)</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <label for="cto34_alias" class="form-label-full">Empresa alias</label>
                                <input id="cto34_alias"
                                       name="cto34_alias"
                                       type="text"
                                       value="{{ old('cto34_alias') }}"
                                       class="form-control form-control-plain input-sm">
                                <label for="cto34_legalName" class="form-label-full margin-top--10">Razón social</label>
                                <input id="cto34_legalName"
                                       name="cto34_legalName"
                                       type="text"
                                       value="{{ old('cto34_legalName') }}"
                                       class="form-control form-control-plain input-sm">
                                <label for="cto34_commercialName" class="form-label-full margin-top--10">Nombre comercial</label>
                                <input id="cto34_commercialName"
                                       name="cto34_commercialName"
                                       type="text"
                                       value="{{ old('cto34_commercialName') }}"
                                       class="form-control form-control-plain input-sm">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_dependency" class="form-label-full">Dependencia</label>
                                <input id="cto34_dependency"
                                       name="cto34_dependency"
                                       type="text"
                                       value="{{ old('cto34_dependency') }}"
                                       class="form-control form-control-plain input-sm">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_especiality" class="form-label-full">Especialidad</label>
                                <input id="cto34_especiality"
                                       name="cto34_especiality"
                                       type="text"
                                       value="{{ old('cto34_especiality') }}"
                                       class="form-control form-control-plain input-sm">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_type" class="form-label-full">Tipo de persona</label>
                                <select name="cto34_type" id="cto34_type" class="form-control input-sm">
                                    <option value="">Seleccionar opción</option>
                                    <option value="Física">Física</option>
                                    <option value="Moral">Moral</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_slogan" class="form-label-full">Slogan</label>
                                <input id="cto34_slogan"
                                       name="cto34_slogan"
                                       type="text"
                                       value="{{ old('cto34_slogan') }}"
                                       class="form-control form-control-plain input-sm">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_website" class="form-label-full">Página web</label>
                                <input id="cto34_website"
                                       name="cto34_website"
                                       type="text"
                                       value="{{ old('cto34_website') }}"
                                       class="form-control form-control-plain input-sm">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_legalId" class="form-label-full">RFC</label>
                                <input id="cto34_legalId"
                                       maxlength="25"
                                       name="cto34_legalId"
                                       type="text"
                                       value="{{ old('cto34_legalId') }}"
                                       class="form-control form-control-plain input-sm">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_imssNum" class="form-label-full">Número de IMSS</label>
                                <input id="cto34_imssNum"
                                       name="cto34_imssNum"
                                       type="text"
                                       value="{{ old('cto34_imssNum') }}"
                                       class="form-control form-control-plain input-sm">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_infonavitNum" class="form-label-full">Número de INFONAVIT</label>
                                <input id="cto34_infonavitNum"
                                       name="cto34_infonavitNum"
                                       type="text"
                                       value="{{ old('cto34_infonavitNum') }}"
                                       class="form-control form-control-plain input-sm">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="cto34_sector" class="form-label-full">Sector</label>
                                <select name="cto34_sector" id="cto34_sector"
                                        class="form-control input-sm">
                                    <option value="">Seleccionar opción</option>
                                    <option value="Privado">Privado</option>
                                    <option value="Público">Público</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="cto34_comments" class="form-label-full">Comentarios</label>
                                <textarea name="cto34_comments" maxlength="4000" id="cto34_comments" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        </div>
    </div>
</div>
{{-- </modal> --}}

{{--
    Modal - #modalFilter
    Formulario para filtrar registros

    <modal>
--}}
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Filtrar usuarios</h4>
                <form action="{{ $navigation['base'] }}/filter" method="GET">
                    <select name="by" id="by" class="form-control margin-bottom--10">
                        <option value="">Elegir filtro</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                        <option value="deleted">Eliminados</option>
                    </select>
                    <button type="submit" class="btn btn-submit btn-default btn-lg-hr">Filtrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- </modal> --}}

{{--
    Modal - #modalDeleteRecord
    Alerta para confirmar que se elimina el registro

    <modal>
--}}
<div class="modal fade" id="modalDeleteRecord" tabindex="-1" role="dialog" aria-labelledby="modalDeleteRecord">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Confirmar</h4>
                <p>Se eliminara el registro <span id="recordDeleteName"></span></p>
                <form action="{{ $navigation['base'].'/action/delete' }}" method="GET" class="row">
                    <input id="recordDeleteId" name="cto34_id" type="hidden">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-success btn-default btn-lg-hr">Confirmar</button>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-danger btn-default btn-lg-hr" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- </modal> --}}
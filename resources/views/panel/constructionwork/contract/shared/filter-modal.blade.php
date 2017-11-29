<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Filtrar</h4>
                <form action="{{ $navigation['base'].'/search' }}" method="GET" class="row">
                    <div id="searchFilter" class="form-group hidden col-sm-12">
                        <label for="searchInputFilter">Busqueda</label>
                        <input id="searchInputFilter" name="q" class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="status">Estatus</label>
                        <select name="status" id="status" class="form-control margin-bottom--10">
                            <option value="">Elegir opción</option>
                            <option value="closed">Cerrados</option>
                            <option value="deleted">Eliminados</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="aggremmentType">Tipo de acuerdo</label>
                        <select name="aggremmentType" id="aggremmentType" class="form-control margin-bottom--10">
                            <option value="">Elegir opción</option>
                            @foreach(agreementtypes_options() as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="assignmentType">Tipo de asignación</label>
                        <select name="assignmentType" id="assignmentType" class="form-control margin-bottom--10">
                            <option value="">Elegir opción</option>
                            @foreach(assignmenttypes_options() as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="modality">Modalidad</label>
                        <select name="modality" id="modality" class="form-control margin-bottom--10">
                            <option value="">Elegir opción</option>
                            @foreach(contractmodality_options() as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <input type="hidden" name="filter" value="true">
                        <button class="btn btn-primary btn-block">
                            <span class="fa fa-filter fa-fw"></span>
                            Aplicar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Filtrar</h4>
                <form action="{{ $navigation['base'].'/search' }}" method="GET" class="row">
                    <div id="searchFilter" class="form-group hidden col-sm-12">
                        <label for="searchInputFilter">Busqueda</label>
                        <input id="searchInputFilter" name="q" class="form-control">
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="status">Estatus</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Elegir opción</option>
                            <!-- <option value="active">Activos</option> -->
                            <option value="closed">Cerrados</option>
                            <option value="deleted">Eliminados</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="orderBy">Orden por</label>
                        <select name="orderBy" id="orderBy" class="form-control">
                            <option value="">Elegir opción</option>
                            <!-- <option value="active">Activos</option> -->
                            <option value="DESC">Recientes</option>
                            <option value="ASC">Antiguos</option>
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12">
                        <button class="btn btn-primary btn-block">
                            <span class="fa fa-filter fa-fw"></span>
                            Aplicar
                        </button>
                        <input type="hidden" name="filter" value="true">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
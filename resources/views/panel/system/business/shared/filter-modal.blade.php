<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title margin-bottom--10">Filtrar</h4>
				<form action="{{ $navigation['base'].'/filter' }}" method="GET">
                    <div id="searchFilter" class="form-group hidden">
                        <label for="searchInputFilter">Busqueda</label>
                        <input id="searchInputFilter" name="q" type="text" class="form-control">
                    </div>
					<label for="status">Estatus</label>
					<select name="status" id="status" class="form-control margin-bottom--10">
						<option value="">Elegir opción</option>
						<option value="active">Activos</option>
						<option value="inactive">Inactivos</option>
						<option value="deleted">Eliminados</option>
					</select>
					<label for="type">Tipo de persona</label>
					<select name="type" id="type" class="form-control margin-bottom--10">
						<option value="">Elegir opción</option>
						<option value="física">Física</option>
						<option value="moral">Moral</option>
					</select>
                    <label for="sector">Sector</label>
                    <select name="sector" id="sector" class="form-control margin-bottom--10">
                        <option value="">Elegir opción</option>
                        <option value="Público">Público</option>
                        <option value="Privado">Privado</option>
                    </select>
                    <button class="btn btn-primary btn-block">
                        <span class="fa fa-filter fa-fw"></span>
                        Aplicar
                    </button>
                </form>
			</div>
		</div>
	</div>
</div>
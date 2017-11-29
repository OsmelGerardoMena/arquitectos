<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title margin-bottom--10">Filtrar</h4>
				<form action="{{ $navigation['base'].'/search' }}" method="GET">
                    <div id="searchFilter" class="form-group hidden">
                        <label for="searchInputFilter">Busqueda</label>
                        <input id="searchInputFilter" name="q" type="text" class="form-control input-sm">
                    </div>
					<label for="building">Por edificio</label>
					<select name="building" id="building" class="form-control margin-bottom--10">
						<option value="">Elegir opción</option>
						@foreach($buildings['all'] as $building)
							<option value="{{ $building->tbUbicaEdificioID }}">{{ $building->UbicaEdificioAlias }}</option>
						@endforeach
					</select>
                    <label for="level">Por nivel</label>
                    <select name="level" id="level" class="form-control margin-bottom--10">
                        <option value="">Elegir opción</option>
                        @foreach($levels['all'] as $level)
                            <option value="{{ $level->tbUbicaNivelID }}">{{ $level->UbicaNivelAlias }}</option>
                        @endforeach
                    </select>
					<label>Tipo</label>
					<select name="type" class="form-control input-sm margin-bottom--10">
						<option value="">Seleccionar opción</option>
						<option value="Interior">Interior</option>
						<option value="Exterior">Exterior</option>
					</select>
					<label>Estatus</label>
					<select name="status" class="form-control input-sm margin-bottom--10">
						<option value="">Elegir opción</option>
						<option value="closed">Cerrados</option>
						<option value="deleted">Eliminados</option>
					</select>
                    <button class="btn btn-primary btn-block">
                        <span class="fa fa-filter fa-fw"></span>
                        Aplicar
                    </button>
					<input type="hidden" name="filter" value="true">
                </form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title margin-bottom--10">Filtrar</h4>
				<form action="{{ $navigation['base'].'/search' }}" method="GET">
                    <div id="searchFilter" class="form-group hidden">
                        <label for="searchInputFilter">Busqueda</label>
                        <input id="searchInputFilter" name="q" type="text" class="form-control">
                    </div>
					<label for="status">Estatus</label>
					<select name="status" id="status" class="form-control margin-bottom--10">
						<option value="">Elegir opción</option>
						<option value="closed">Cerrados</option>
						<option value="deleted">Eliminados</option>
					</select>
					<!--<label for="location" class="form-label-full">Localidad</label>
					<select name="location" id="location" class="form-control input-sm margin-bottom--10">
						<option value="">Seleccionar opción</option>
						@foreach(binnacle_note_type_options() as $option)
							<option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
						@endforeach
					</select>-->
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
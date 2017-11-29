<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title margin-bottom--10">Filtrar teléfonos</h4>
				<form action="{{ $navigation['base'] }}/filter" method="GET">
					<select name="by" id="by" class="form-control margin-bottom--10">
						<option value="">Elegir filtro</option>
						<option value="active">Activos</option>
						<option value="inactive">Inactivos</option>
						<option value="closed">Cerrados</option>
						<option value="deleted">Eliminados</option>
					</select>
					<button type="submit" class="btn btn-submit btn-default btn-lg-hr">Filtrar</button>
				</form>
			</div>
		</div>
	</div>
</div>
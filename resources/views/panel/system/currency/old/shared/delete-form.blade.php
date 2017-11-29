<div id="confirmDeleteAlert" class="alert-delete" style="display: none">
	<p class="h4 margin-top--20">Se eliminara {{ $title }}  <b>{{ $name  }}</b></p>
	<form action="{{ $navigation['base'] }}/action/delete" method="post">
		<input type="hidden" name="cto34_id" value="{{ $id }}">
		<input type="hidden" name="_method" value="DELETE">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<button class="btn btn-success">Confirmar</button>
		<button id="cancelDeleteButton" class="btn btn-danger" type="button">Cancelar</button>
	</form>
</div>
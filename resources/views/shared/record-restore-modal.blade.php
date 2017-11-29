<div class="modal fade" id="modalRestoreRecord" tabindex="-1" role="dialog" aria-labelledby="modalRestoreRecord">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4 class="modal-title margin-bottom--10">Restaurar registro</h4>
                <p class="small">Se restaurara éste registro, favor de confirmar.</p>
                <form action="{{ $navigation['base'].'/action/restore' }}" method="post" class="row">
                    @if (isset($work))
                        <input type="hidden" name="cto34_work" value="{{ $work }}">
                    @endif
                    <input type="hidden" name="cto34_id" value="{{ $id }}">
                    <input type="hidden" name="_method" value="put">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-sm-6">
                        <button class="btn btn-primary btn-block">Confirmar</button>
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-danger btn-block" type="button" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
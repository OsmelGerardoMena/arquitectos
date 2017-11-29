<div class="modal fade" id="modalDeleteRecord" tabindex="-1" role="dialog" aria-labelledby="modalDeleteRecord">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4 class="modal-title margin-bottom--10">Eliminar registro</h4>
                <p class="small">Se eliminara éste registro, favor de confirmar.</p>
                <form action="{{ $navigation['base'].'/action/delete' }}" method="post" class="row">
                    @if (isset($work))
                        <input type="hidden" name="cto34_work" value="{{ $work }}">
                    @endif
                    <input type="hidden" name="cto34_id" value="{{ $id }}">
                    <input type="hidden" name="_method" value="DELETE">
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
<div class="modal fade" id="modalCloseRecord" tabindex="-1" role="dialog" aria-labelledby="modalCloseRecord">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4 class="modal-title margin-bottom--10">Cerrar registro</h4>
                <p class="small">Se cerrara éste registro, favor de confirmar.</p>
                <form action="" method="post" class="row">
                    <div class="col-sm-6">
                        <button id="closeRecordButton" class="btn btn-primary btn-block" type="button">Confirmar</button>
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-danger btn-block" type="button" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                    <input type="hidden" name="_base" value="{{ Request::fullUrl() }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="put">
                    <input type="hidden" name="cto34_id" value="">
                    <input type="hidden" name="cto34_work" value="">
                </form>
            </div>
        </div>
    </div>
</div>
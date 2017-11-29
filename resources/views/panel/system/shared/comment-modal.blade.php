<div class="modal fade" id="modalComment" tabindex="-1" role="dialog" aria-labelledby="modalComment">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Comentar</h4>
                <form action="{{ url('panel/comments/save') }}" method="post" accept-charset="utf-8">
                    <textarea name="cto34_comment" id="cto34_comment" cols="30" rows="10" class="form-control" maxlength="4000" required></textarea>                    
                    <input type="hidden" name="cto34_table" value="{{ $model->getTable() }}">
                    <input type="hidden" name="cto34_tableId" value="{{ $model->getKey() }}">
                    <input type="hidden" name="cto34_uid" value="{{ Auth::id() }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_base" value="{{ Request::fullUrlWithQuery(['_tab' => 'comments']) }}">
                    <button class="btn btn-primary margin-top--10">
                        <span class="fa fa-floppy-o fa-fw"></span>
                        Guardar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
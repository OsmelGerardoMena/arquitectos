<div class="modal fade" id="modalComment" tabindex="-1" role="dialog" aria-labelledby="modalComment">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Comentar</h4>
                <form action="{{ url('panel/comments/save') }}" method="post" accept-charset="utf-8">
                    <textarea name="cto34_comment" id="cto34_comment" cols="30" rows="3" class="form-control" maxlength="4000" required></textarea>                    
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

<div class="modal fade" id="showCommentModal" tabindex="-1" role="dialog" aria-labelledby="showCommentModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-right" style="position: absolute; width: 100%; top: 0; left: 0; padding: 10px">
                    <button type="button" class="modalSaveButton btn btn-primary btn-sm modalNavAction disabled">
                        <span class="fa fa-floppy-o fa-fw"></span>
                    </button>
                    <button type="button" class="modalUpdateButton btn btn-primary btn-sm modalNavAction user-own">
                        <span class="fa fa-pencil fa-fw"></span>
                    </button>
                    <button type="button" class="modalDeleteButton btn btn-danger btn-sm user-own">
                        <span class="fa fa-trash fa-fw"></span>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <span class="fa fa-times fa-fw"></span>
                        </span>
                    </button>
                </div>
                <h4 class="modal-title margin-bottom--10">Comentario</h4>
                <div class="modalContent row">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Autor</label>
                        <p id="modalCommentAuthor" class="help-block"> - </p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Fecha timestamp</label>
                        <p id="modalCommentDate" class="help-block"> - </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Comentario</label>
                        <p id="modalCommentDescription" style="height:170px;" class="help-block help-block--textarea"> - </p>
                    </div>
                </div>
                <div class="modalForm row hidden">
                    <form class="saveForm" action="{{ url('panel/comments/update') }}" method="post" accept-charset="utf-8">
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Autor</label>
                            <input name="cto34_author" type="text" class="form-control input-sm" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label-full">Fecha timestamp</label>
                            <input name="cto34_date" type="text" class="form-control input-sm" readonly>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label-full">Comentario</label>
                            <textarea maxlength="4000" name="cto34_comment" rows="8" class="form-control"></textarea>
                        </div>
                        <input name="cto34_id" type="hidden">
                        <input name="_method" type="hidden" value="put">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input name="_base" type="hidden" value="{{ Request::fullUrlWithQuery(['_tab' => 'comments']) }}">
                    </form>
                    <form class="deleteForm" action="{{ url('panel/comments/delete') }}" method="post" accept-charset="utf-8">
                        <input name="cto34_id" type="hidden">
                        <input name="_method" type="hidden" value="delete">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input name="_base" type="hidden" value="{{ Request::fullUrlWithQuery(['_tab' => 'comments']) }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

namespace App\Http\Controllers\Panel;

use URL;
use Validator;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ConstructionWork;

use App\Models\Comment;

class CommentController extends AppController
{
    private $viewsPath = 'panel.comment.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function doSave(Request $request) {

        $redirect = $request->input('_base');

        $rules = [
            'cto34_comment' => 'required',
            'cto34_table' => 'required',
            'cto34_tableId' => 'required',
            'cto34_uid' => 'required',
        ];

        $messages = [
            'cto34_comment.required' => 'Comentario requerido.',
            'cto34_table.required' => 'Nombre de tablara requerida.',
            'cto34_tableId.required' => 'Id de registro requerido.',
            'cto34_uid.required' => 'Id de autor requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $comment = new Comment();
        $comment->setConnection('mysql-writer');

        $comment->Comentario = $request->input('cto34_comment');
        $comment->tbCTOUsuarioID_Comentario = $request->input('cto34_uid');
        $comment->ComentarioTabla = $request->input('cto34_table');
        $comment->ComentarioTablaID = $request->input('cto34_tableId');
        $comment->created_at = date("d-m-Y H:i");

        if (!$comment->save()) {
            return redirect($redirect)
                ->withErrors(['No se puede guarda el comentario, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Comentario agregado correctamente.');
    }

    public function doUpdate(Request $request) {

        $redirect = $request->input('_base');
        $id = $request->input('cto34_id');

        $rules = [
            'cto34_comment' => 'required',
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_comment.required' => 'Comentario requerido.',
            'cto34_id.required' => 'Id de comentario requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $comment = Comment::find($id)->setConnection('mysql-writer');
        
        $comment->Comentario = $request->input('cto34_comment');
        $comment->updated_at = date("d-m-Y H:i");
        if (!$comment->save()) {
            return redirect($redirect)
                ->withErrors(['No se puede actualizar el comentario, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Comentario actualizado correctamente.');
    }

    public function doDelete(Request $request) {

        $redirect = $request->input('_base');
        $id = $request->input('cto34_id');

        $rules = [
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de comentario requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $comment = Comment::find($id)->setConnection('mysql-writer');

        if (!$comment->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el comentario, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Comentario eliminado correctamente.');
    }
}
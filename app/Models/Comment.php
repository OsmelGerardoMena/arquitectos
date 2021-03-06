<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    /**
     * Nombre de la tabla en bd
     *
     * @var string
     */
    protected $table = 'tbComentario';

    /**
     * la llave primaria de la tabla
     *
     * @var string
     */
    protected $primaryKey = 'tbComentarioID';

    /**
     * Inhabilitar timestamp para insert y update
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->RegistroUsuario = Auth::id();
        });

        self::updating(function ($model) {
            $model->RegistroUsuario = Auth::id();
        });

        self::deleting(function($model){
            $model->RegistroUsuario = Auth::id();
            $model->save();
        });
    }

    public function user() {
        return $this->hasOne('\App\Models\User', 'tbCTOUsuarioID', 'tbCTOUsuarioID_Comentario');
    }

}
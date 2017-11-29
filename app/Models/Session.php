<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Session extends Model {

    /**
     * Nombre de la tabla en bd
     *
     * @var string
     */
    protected $table = 'tbCTOSesion';

    /**
     * la llave primaria de la tabla
     *
     * @var string
     */
    protected $primaryKey = 'TbCTOSesionID';

    /**
     * Inhabilitar timestamp para insert y update
     *
     * @var bool
     */
    public $timestamps = false;

    public function user() {
        return $this->hasOne('\App\Models\User', 'TbCTOUsuarioID', 'tbUsuarioID_Sesion');
    }
}
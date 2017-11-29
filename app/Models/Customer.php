<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model {

    use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbCliente';
    
    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'TbClientesID';

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

    public function business() {
        return $this->hasOne('App\Models\Business', 'tbDirEmpresaID', 'TbDirEmpresaID_Clientes');
    }

    public function person() {
        return $this->hasOne('App\Models\Person', 'tbDirPersonaID', 'tbDirPersonaEmpresaID_BitacoraDestinatario');
    }
}
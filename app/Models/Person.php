<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model {

    use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbDirPersona';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbDirPersonaID';

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

    public function personBusiness() {
        return $this->hasOne('App\Models\PersonBusiness', 'tbDirPersonaID_DirPersonaEmpresa', 'tbDirPersonaID');
    }

    public function work() {
        return $this->hasOne('\App\Models\PersonWork', 'tbDirPersonaEmpresaID_DirPersonaEmpresaObra', 'tbDirPersonaID');
    }

    public function phones() {
        return $this->hasMany('\App\Models\Phone', 'tbDirPersonaID_DirTelefono', 'tbDirPersonaID');
    }

    public function comments() {
        return $this->hasMany('\App\Models\Comment','ComentarioTablaID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }
}
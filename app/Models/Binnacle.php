<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Database\Eloquent\Builder;

class Binnacle extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbBitacora';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbBitacoraID';

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

        /*static::addGlobalScope('RegistroCerrado', function(Builder $builder) {
            $builder->where('RegistroCerrado', '=', 0);
        });*/

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

    public function contract() {
        return $this->hasOne('App\Models\Contract', 'tbContratoID', 'tbContratoID_Bitacora');
    }

    public function author() {
        return $this->hasOne('App\Models\PersonBusinessWork', 'tbDirPersonaEmpresaObraID', 'tbDirPersonaEmpresaObraID_BitacoraAutor');
    }

    public function receiver() {
        return $this->hasOne('App\Models\PersonBusinessWork', 'tbDirPersonaEmpresaObraID', 'tbDirPersonaEmpresaObraID_BitacoraDestinatario');
    }

    public function auth() {
        return $this->hasOne('App\Models\User', 'tbCTOUsuarioID', 'tbCTOUsuarioID_BitacoraAutoriza');
    }

    public function comments() {
        return $this->hasMany('\App\Models\Comment','ComentarioTablaID', 'tbBitacoraID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }

    public function catcher() {
        return $this->hasOne('\App\Models\User', 'tbCTOUsuarioID', 'tbCTOUsuarioID_BitacoraCaptura');
    }

    public function modifier() {
        return $this->hasOne('\App\Models\User', 'tbCTOUsuarioID', 'tbCTOUsuarioID_BitacoraModifica');
    }
}
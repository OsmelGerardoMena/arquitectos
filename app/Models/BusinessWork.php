<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessWork extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbDirEmpresaObra';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbDirEmpresaObraID';

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

    public function comments() {
       return $this->hasMany('\App\Models\Comment','ComentarioTablaID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }
    
    public function business() {
        return $this->hasMany('App\Models\Business', 'tbDirEmpresaID', 'tbDirEmpresaID_DirEmpresaObra');
    }

    public function personsWork() {
        return $this->hasMany('App\Models\PersonBusinessWork', 'tbDirEmpresaID_DirPersonaEmpresaObra', 'tbDirEmpresaID_DirEmpresaObra');
    }

    public function businessOne() {
        return $this->hasOne('App\Models\Business', 'tbDirEmpresaID', 'tbDirEmpresaID_DirEmpresaObra');
    }

    public function group(){
        return $this->hasOne('App\Models\Group', 'tbDirGrupoID', 'tbDirGrupoID_DirEmpresaObra');
    }

    public function phones() {
        return $this->hasMany('App\Models\Phone', 'tbDirEmpresaID_DirTelefono', 'tbDirEmpresaID_DirEmpresaObra');
    }

    public function proceedings() {
        return $this->hasMany('App\Models\Proceedings', 'tbDirEmpresaID_EmpresaActa', 'tbDirEmpresaID_DirEmpresaObra');
    }

    public function partners() {
        return $this->hasMany('App\Models\BusinessPartner', 'tbDirEmpresaID_EmpresaSocio', 'tbDirEmpresaID_DirEmpresaObra');
    }
}
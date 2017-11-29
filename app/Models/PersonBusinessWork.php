<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonBusinessWork extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbDirPersonaEmpresaObra';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbDirPersonaEmpresaObraID';

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
        return $this->hasOne('App\Models\PersonBusiness', 'tbDirPersonaEmpresaMiEmpresaID', 'tbDirPersonaEmpresaID_DirPersonaEmpresaObra');
    }

    public function personsBusiness() {
        return $this->hasMany('App\Models\PersonBusiness', 'tbDirPersonaEmpresaMiEmpresaID', 'tbDirPersonaEmpresaID_DirPersonaEmpresaObra');
    }

    public function businessWork() {
        return $this->hasOne('App\Models\BusinessWork', 'tbDirEmpresaObraID', 'tbDirEmpresaID_DirPersonaEmpresaObra');
    }

    public function business() {
        return $this->hasOne('App\Models\Business', 'tbDirEmpresaID', 'tbDirEmpresaID_DirPersonaEmpresaObra');
    }

    public function comments() {
        return $this->hasMany('\App\Models\Comment','ComentarioTablaID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }
}
<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phone extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbDirTelefono';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbDirTelefonoID';

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
        return $this->hasOne('\App\Models\Business', 'tbDirEmpresaID', 'tbDirEmpresaID_DirTelefono');
    }

    public function person() {
        return $this->hasOne('\App\Models\Person', 'tbDirPersonaID', 'tbDirPersonaID_DirTelefono');
    }
    
    public function comments() {
        return $this->hasMany('\App\Models\Comment','ComentarioTablaID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }

}